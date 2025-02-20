<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageModel;
use App\Models\AnnonceModel;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // //Affiche tous les messages liés à une annonce spécifique.
    // public function index($annonceId)
    // {
    //     $userId = Auth::id();

    //     // Récupérer les messages pour l'annonce
    //     $messages = MessageModel::with(['expediteur', 'destinataire'])
    //         ->where('annonce_id', $annonceId)
    //         ->where(function ($query) use ($userId) {
    //             $query->where('expediteur_id', $userId)
    //                   ->orWhere('destinataire_id', $userId);
    //         })
    //         ->orderBy('dateMsg', 'asc')
    //         ->get();

    //     return response()->json($messages);
    // }
    // /**
    //  * Envoie un message à un utilisateur concernant une annonce.
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'contenu' => 'required|string',
    //         'destinataire_id' => 'required|exists:utilisateur,IdUt',
    //         'annonce_id' => 'required|exists:annonce,IdAn',
    //     ]);

    //     $message = MessageModel::create([
    //         'contenu' => $request->input('contenu'),
    //         'dateMsg' => now(),
    //         'expediteur_id' => Auth::id(),
    //         'destinataire_id' => $request->input('destinataire_id'),
    //         'annonce_id' => $request->input('annonce_id'),
    //     ]);

    //     return response()->json($message, 201);
    // }
    // /**
    //  * Supprime un message si l'utilisateur est l'expéditeur.
    //  */
    // public function destroy($id)
    // {
    //     $userId = Auth::id();

    //     // Vérifier que l'utilisateur est l'expéditeur du message
    //     $message = MessageModel::where('IdMsg', $id)
    //         ->where('expediteur_id', $userId)
    //         ->first();

    //     if (!$message) {
    //         return response()->json(['message' => 'Message introuvable ou non autorisé.'], 404);
    //     }

    //     $message->delete();

    //     return response()->json(['message' => 'Message supprimé avec succès.']);
    // }
     /**
     * Affiche tous les messages liés à une annonce spécifique.
     */
    public function index($annonceId)
    {
        // Récupérer les messages pour une annonce spécifique
        $messages = MessageModel::where('annonce_id', $annonceId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Enregistre un nouveau message concernant une annonce.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sujet' => 'required|string|max:255',
            'nom' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'contenu' => 'required|string',
            'annonce_id' => 'required|exists:annoncee,id',
        ]);

        $message = MessageModel::create([
            'sujet' => $request->input('sujet'),
            'nom' => $request->input('nom'),
            'telephone' => $request->input('telephone'),
            'email' => $request->input('email'),
            'contenu' => $request->input('contenu'),
            'annonce_id' => $request->input('annonce_id'),
        ]);

        return response()->json($message, 201);
    }

    /**
     * Supprime un message (accessible uniquement via un ID direct).
     */
    public function destroy($id)
    {
        // Vérifier l'existence du message
        $message = MessageModel::find($id);

        if (!$message) {
            return response()->json(['message' => 'Message introuvable.'], 404);
        }

        $message->delete();

        return response()->json(['message' => 'Message supprimé avec succès.']);
    }

}
