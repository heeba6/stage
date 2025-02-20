<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UtilisateurModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:utilisateur',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|string'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    // Valider le reCAPTCHA auprès de Google
    $recaptchaResponse = $request->input('recaptcha_token');
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET_KEY'),
        'response' => $recaptchaResponse,
    ]);

    $result = $response->json();
    if (!$result['success']) {
        return response()->json(['error' => 'Échec de la validation reCAPTCHA'], 400);
    }

    try {
        $hashedPassword = Hash::make($request->password);

        // Créer l'utilisateur avec un statut non vérifié
        $user = UtilisateurModel::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $hashedPassword,
            'role' => $request->role,
            'email_verified_at' => null, // Marquer l'email comme non vérifié
        ]);

        // Générer un token de vérification
        $verificationToken = Str::random(64);

        // Sauvegarder le token dans une table dédiée ou directement dans l'utilisateur
        try {
            DB::table('email_verifications')->insert([
                'email' => $user->email,
                'token' => $verificationToken,
                'created_at' => now(),
            ]);
            \Log::info('Token de vérification enregistré pour l\'email : ' . $user->email);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'enregistrement du token de vérification : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du token de vérification'], 500);
        }

        // Envoyer un email de vérification
        try {
            Mail::send('emails.verify-email', ['token' => $verificationToken], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Vérifiez votre adresse email');
            });
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de vérification: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'envoi de l\'email'], 500);
        }

        return response()->json([
            'message' => 'Un email de vérification a été envoyé. Veuillez vérifier votre boîte de réception.',
        ], 201);
    } catch (\Exception $e) {
        //return response()->json(['error' => $e->getMessage()], 500);
        \Log::error('Erreur API : ' . $e->getMessage());
        return response()->json(['error' => 'Erreur inconnue'], 500);
    }
    }
    public function verifyEmail($token)
    {
        // return redirect('http://localhost:5173/login?verified=1');
         // Rechercher le token dans la table email_verifications
    $verificationRecord = DB::table('email_verifications')->where('token', $token)->first();

    if (!$verificationRecord) {
        return response()->json(['error' => 'Token de vérification invalide ou expiré.'], 400);
    }

    // Rechercher l'utilisateur par email
    $user = UtilisateurModel::where('email', $verificationRecord->email)->first();
    if (!$user) {
        return response()->json(['error' => 'Utilisateur introuvable.'], 404);
    }

    // Marquer l'email comme vérifié
    $user->email_verified_at = now();
    $user->save();

    // Supprimer le token de vérification
    DB::table('email_verifications')->where('token', $token)->delete();

    Log::info('Email vérifié avec succès pour l\'utilisateur : ' . $user->email);

    // Rediriger vers la page de connexion avec un message de succès
    return redirect('http://localhost:5173/login?verified=1');
       
    }


    public function login(Request $request)
    {
    
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = UtilisateurModel::where('email', $request->email)->first();

        if (!$user) {
            \Log::info("Utilisateur introuvable avec cet email : " . $request->email);
            return response()->json([
                'message' => 'Email incorrect',
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            \Log::info("Mot de passe incorrect pour l'email : " . $request->email);
            return response()->json([
                'message' => 'Mot de passe incorrect',
            ], 401);
        }

        // Vérification si l'email est validé
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Votre email n\'a pas encore été vérifié. Veuillez vérifier votre boîte de réception.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => [
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role
            ]
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ], 200);
    }

    //une méthode pour envoyer un lien de réinitialisation de mot de passe
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:utilisateur,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Lien de réinitialisation envoyé à votre email.'], 200)
            : response()->json(['error' => 'Échec de l\'envoi du lien de réinitialisation.'], 500);

    }
    // gérer la réinitialisation du mot de passe avec un token
    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:utilisateur,email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        // Générer un token de réinitialisation
        $token = Password::createToken(UtilisateurModel::where('email', $request->email)->first());
    
       // Construire le lien de réinitialisation
        $resetLink = 'http://localhost:5173/passwordReset?email=' . urlencode($request->email) . '&token=' . urlencode($token);
        // Envoyer l'e-mail avec le lien de réinitialisation
        // Vous pouvez utiliser un service d'envoi d'e-mails comme Mailgun, SendGrid, ou Laravel Mail
        // Exemple avec Laravel Mail :
        \Mail::send('emails.reset_password', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Réinitialisation de votre mot de passe');
        });

        return response()->json(['message' => 'Lien de réinitialisation envoyé à votre email.'], 200);
    
    }
    public function completeResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:utilisateur,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Attempt to reset the password using Laravel's Password broker
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Mot de passe réinitialisé avec succès.'], 200)
            : response()->json(['error' => 'Échec de la réinitialisation du mot de passe.'], 500);
    }

    

}
