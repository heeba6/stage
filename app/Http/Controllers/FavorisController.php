<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FavorisModel;
use App\Models\AnnonceModel;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    // Affiche la liste des annonces favorites de l'utilisateur authentifié.
    public function index()
    {
        $userId = Auth::id();

        // Récupérer les annonces favorites de l'utilisateur
        $favoris = FavorisModel::with('annonce') // Ajout de la relation annonce si nécessaire
            ->where('IdUt', $userId)
            ->get();

        return response()->json($favoris);
    }

    // Ajoute une annonce aux favoris de l'utilisateur.
    public function store(Request $request)
    {
        $request->validate([
            'annonce_id' => 'required|exists:annoncee,id', // Vérifier l'existence de l'annonce
        ]);
    
        $userId = Auth::id();
        $annonceId = $request->input('annonce_id');
    
        // Vérifier si l'annonce est déjà dans les favoris
        $favorisExists = FavorisModel::where('IdUt', $userId)
            ->where('annonce_id', $annonceId)
            ->exists();
    
        if ($favorisExists) {
            return response()->json(['message' => 'Cette annonce est déjà dans vos favoris.'], 200);  // Renvoi d'un succès sans erreur
        }
    
        // Ajouter l'annonce aux favoris
        $favoris = FavorisModel::create([
            'IdUt' => $userId,
            'annonce_id' => $annonceId,
        ]);
    
        return response()->json(['message' => 'Favori ajouté avec succès.'], 201);
    }
    

    // Supprime une annonce des favoris de l'utilisateur.
    public function destroy($id)
    {
        $userId = Auth::id();

        // Vérifier si le favori existe et appartient à l'utilisateur
        $favoris = FavorisModel::where('id', $id)
            ->where('IdUt', $userId)
            ->first();

        if (!$favoris) {
            return response()->json(['message' => 'Favori introuvable ou non autorisé.'], 404);
        }

        $favoris->delete();

        return response()->json(['message' => 'Favori supprimé avec succès.']);
    }

    // Vérifie si un élément est dans les favoris.
    public function check(Request $request)
    {
        $request->validate([
            'annonce_id' => 'required|integer',
        ]);

        $isFavorite = FavorisModel::where('IdUt', Auth::id())
            ->where('annonce_id', $request->annonce_id)
            ->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }

    // Supprime tous les favoris de l'utilisateur.
    public function clear()
    {
        $userId = Auth::id();

        FavorisModel::where('IdUt', $userId)->delete();

        return response()->json(['message' => 'Tous les favoris ont été supprimés.']);
    }

    // Ajoute un favori avec vérification
    public function addToFavoris(Request $request)
{
    // Vérification de l'ID de l'annonce
    $validated = $request->validate([
        'annonce_id' => 'required|exists:annoncee,id', // Vérification sur la bonne table 'annoncee'
    ]);

    // Récupérer l'utilisateur authentifié
    $user = auth()->user(); // Cette méthode récupère l'utilisateur à partir du token d'authentification

    if (!$user) {
        return response()->json(['message' => 'Utilisateur non authentifié'], 401); // Si l'utilisateur n'est pas authentifié
    }

    // Vérifiez si l'annonce est déjà dans les favoris
    if ($user->favoris()->where('annonce_id', $validated['annonce_id'])->exists()) {
        return response()->json(['message' => 'Déjà dans vos favoris'], 400);
    }

    // Ajout des favoris
    $user->favoris()->create([
        'annonce_id' => $validated['annonce_id'],
    ]);

    return response()->json(['message' => 'Ajouté aux favoris avec succès']);
}


    // Supprime un favori
    public function removeFromFavoris($annonceId)
    {
        $user = auth()->user();

        // Vérifiez si l'annonce est dans les favoris
        $favori = $user->favoris()->where('annonce_id', $annonceId)->first();

        if (!$favori) {
            return response()->json(['message' => 'Favori introuvable'], 404);
        }

        $favori->delete();

        return response()->json(['message' => 'Supprimé des favoris avec succès']);
    }
    public function getUserFavoris()
    {
        // Récupérer l'utilisateur connecté
        $user = auth()->user();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }

        // Récupérer les favoris avec les annonces associées pour l'utilisateur connecté
        $favoris = \App\Models\FavorisModel::with('annonce') // Relation avec les annonces
            ->where('IdUt', $user->IdUt) // Filtrer par l'utilisateur connecté
            ->get();

        // Retourner les annonces uniquement
        $annonces = $favoris->map(function ($favori) {
            return $favori->annonce;
        });

        return response()->json($annonces);
    }


}
