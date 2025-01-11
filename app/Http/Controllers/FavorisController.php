<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favoris;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
//Affiche la liste des annonces favorites de l'utilisateur authentifié.
   public function index()
   {
       $userId = Auth::id();
    // Récupérer les annonces favorites de l'utilisateur
       $favoris = Favoris::with('annonce')->where('IdUt', $userId)->get();

       return response()->json($favoris);
   }

   //Ajoute une annonce aux favoris de l'utilisateur.
    public function store(Request $request)
    {
        $request->validate([
            'IdAn' => 'required|exists:annonces,IdAn',
        ]);

        $userId = Auth::id();
        $annonceId = $request->input('IdAn');

        // Vérifier si l'annonce est déjà dans les favoris
        $favorisExists = Favoris::where('IdUt', $userId)
                                ->where('IdAn', $annonceId)
                                ->exists();

        if ($favorisExists) {
            return response()->json(['message' => 'Cette annonce est déjà dans vos favoris.'], 409);
        }

        // Ajouter l'annonce aux favoris
        $favoris = Favoris::create([
            'IdUt' => $userId,
            'annonce_id' => $annonceId,
        ]);

        return response()->json($favoris, 201);
    }
    //Supprime une annonce des favoris de l'utilisateur.
    public function destroy($id)
    {
        $userId = Auth::id();

        // Vérifier si le favori existe et appartient à l'utilisateur
        $favoris = Favoris::where('IdFa', $id)->where('IdUt', $userId)->first();

        if (!$favoris) {
            return response()->json(['message' => 'Favori introuvable ou non autorisé.'], 404);
        }

        $favoris->delete();

        return response()->json(['message' => 'Favori supprimé avec succès.']);
    }
}
