<?php
namespace App\Http\Controllers;
use App\Models\UtilisateurModel;
use App\Models\AnnonceModel;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index() // Récupérer toutes les annonces
    {
        $annonces = AnnonceModel::all();
        return response()->json($annonces);
    }

    public function create() // Formulaire de création d'annonce
    {
        return response()->json([
            'message' => 'Ready to create a new announcement.'
        ], 200); // 200 OK status
    }

    public function store(Request $request) // Enregistrer une nouvelle annonce
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'surface_habitable' => 'required|numeric',
            'adresse' => 'required|string',
            // 'ville' => 'required|string',
            'ville' => 'string',
            'vocation' => 'required|string',
            'type' => 'required|string',
            'datePub' => 'date',
            'etat' => 'nullable|string',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'espaceExterieur' => 'nullable|array', // Validation pour espaceExterieur
            'parking' => 'nullable|array', // Validation pour parking
            'chauffage' => 'nullable|array', // Validation pour chauffage
            'proximite' => 'nullable|array', // Validation pour proximite
        ]);
    
        // Gestion des photos téléchargées
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $photoPaths[] = $photo->store('photos', 'public');
                }
            }
        }
    
        // Création de l'annonce avec toutes les informations
        $annonce = AnnonceModel::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->prix,
            'surface_habitable' => $request->surface_habitable,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'vocation' => $request->vocation,
            'type' => $request->type,
            'datePub' => $request->datePub,
            //'etat' => $request->etat,
            'etat' => $request->etat ?? 'en attente', // Valeur par défaut si `etat` est nul
            'IdUt' => auth()->id(),
            'photos' => json_encode($photoPaths),
            'espaceExterieur' => json_encode($request->espaceExterieur), // Nouveau champ
            'parking' => json_encode($request->parking), // Nouveau champ
            'chauffage' => json_encode($request->chauffage), // Nouveau champ
            'proximite' => json_encode($request->proximite), // Nouveau champ
        ]);
    
        return response()->json([
            'message' => 'Annonce created successfully.',
            'annonce' => $annonce,
            'photo_urls' => $photoPaths,
        ], 201);
    }

    public function show($id) // Afficher une annonce spécifique
    {
        $annonce = AnnonceModel::findOrFail($id);
        if ($annonce) {
            // Décodez les champs JSON s'ils sont stockés sous forme de chaîne JSON
            $annonce->espaceExterieur = json_decode($annonce->espaceExterieur, true);
            $annonce->parking = json_decode($annonce->parking, true);
            $annonce->chauffage = json_decode($annonce->chauffage, true);
            $annonce->proximite = json_decode($annonce->proximite, true);
    
            return response()->json($annonce);
        }
    
        return response()->json(['message' => 'Annonce non trouvée'], 404);
    }
    public function edit($id) // Formulaire d'édition d'une annonce
    {
        $annonce = AnnonceModel::findOrFail($id);
        return response()->json($annonce);
    }
    public function update(Request $request, $id) // Mettre à jour une annonce
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'surface_habitable' => 'required|numeric',
            'adresse' => 'required|string',
            'ville' => 'required|string',
            'vocation' => 'required|string',
            'type' => 'required|string',
            'datePub' => 'nullable|date', // validation améliorée
            'etat' => 'nullable|string', // validation améliorée
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'espaceExterieur' => 'nullable|array',
            'parking' => 'nullable|array',
            'chauffage' => 'nullable|array',
            'proximite' => 'nullable|array',
        ]);
    
        $annonce = AnnonceModel::findOrFail($id);
    
        // Récupérer les anciennes photos et les supprimer si nécessaire
        $photoPaths = json_decode($annonce->photos, true) ?? [];
    
        if ($request->hasFile('photos')) {
            // Supprimer les anciennes photos du stockage
            foreach ($photoPaths as $photo) {
                $photoPath = storage_path('app/public/' . $photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
    
            // Ajouter les nouvelles photos
            foreach ($request->file('photos') as $photo) {
                if ($photo->isValid()) {
                    $photoPaths[] = $photo->store('photos', 'public');
                }
            }
        }
    
        // Mettre à jour l'annonce avec toutes les informations
        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->prix,
            'surface_habitable' => $request->surface_habitable,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'vocation' => $request->vocation,
            'type' => $request->type,
            'datePub' => $request->datePub,
            'etat' => $request->etat,
            'photos' => json_encode($photoPaths),
            'espaceExterieur' => $request->has('espaceExterieur') ? json_encode($request->espaceExterieur) : null,
            'parking' => $request->has('parking') ? json_encode($request->parking) : null,
            'chauffage' => $request->has('chauffage') ? json_encode($request->chauffage) : null,
            'proximite' => $request->has('proximite') ? json_encode($request->proximite) : null,
        ]);
    
        return response()->json([
            'message' => 'Annonce updated successfully.',
            'annonce' => $annonce,
            'photo_urls' => $photoPaths,
        ], 200);
    }
    

    public function destroy($id) // Supprimer une annonce
    {
        $annonce = AnnonceModel::findOrFail($id);

        // Supprimer les photos existantes
        //$photoPaths = json_decode($annonce->photo, true);
        $photoPaths = $annonce->photo ? json_decode($annonce->photo, true) : [];
        foreach ($photoPaths as $photo) {
            if (file_exists(storage_path('app/public/' . $photo))) {
                unlink(storage_path('app/public/' . $photo));
            }
        }

        // Supprimer l'annonce
        $annonce->delete();

        return response()->json([
            'message' => 'Annonce deleted successfully.'
        ]);
    }

    public function countAnnonces()
{
    $count = AnnonceModel::count(); // Compte le nombre total d'annonces
    return response()->json(['count' => $count]);
}
// public function getAnnoncesUtilisateur()
// {
//     try {
//         $user = auth()->user();

//         if (!$user) {
//             return response()->json(['message' => 'Utilisateur non authentifié'], 401);
//         }

//         // Vérifie si la colonne est bien 'IdUt' ou 'user_id' dans ta base de données
//         $annonces = AnnonceModel::where('IdUt', $user->id)->get(); 

//         if ($annonces->isEmpty()) {
//             return response()->json(['message' => 'Aucune annonce trouvée.'], 200);
//         }

//         return response()->json($annonces);
//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Erreur serveur: ' . $e->getMessage()], 500);
//     }
// }
public function getAnnoncesUtilisateur()
{
    dd(auth()->user()); // Vérifier si l'utilisateur est bien authentifié
}





}
