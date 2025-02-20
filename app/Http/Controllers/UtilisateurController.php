<?php

namespace App\Http\Controllers;
use App\Models\UtilisateurModel;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()// Retrieve all users
    {
        $utlisateurs = UtilisateurModel::all();
        // return response()->json([
        //     'message' => 'Liste des utilisateurs récupérée avec succès.',
        //     'utilisateurs' => $utilisateurs], 200);
        return response()->json($utlisateurs);
        //return view('utlisateurs.index', compact('utlisateurs'));
    }
    public function create()
    {
        return response()->json([
            'message' => 'Prêt à créer un nouvel utilisateur.'
        ], 200);
        //return view('utlisateurs.create');// Show the form for creating a new announcement
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utlisateur,email',
            'password' => 'required|string|min:8|confirmed',
            
            'role' => 'required|string|in:user,admin', // Par exemple, les rôles autorisés
        ]);

        UtilisateurModel::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash du mot de passe
            'role' => $request->role,
        ]);
        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'utilisateur' => $utilisateur
        ], 201);

        //return redirect()->route('utlisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }
    public function show($IdUt)
    {
        // $utlisateur = UtilisateurModel::findOrFail($id);
        // return view('utlisateurs.show', compact('utlisateur'));
        $utilisateur = UtilisateurModel::find($IdUt);
        //dd($utilisateur);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404); // Retourner une erreur si l'utilisateur n'est pas trouvé
        }

        return response()->json($utilisateur);
    }
    public function update(Request $request,$IdUt)
    {
    // 🔍 Vérifier si l'utilisateur existe
    //$utilisateur = UtilisateurModel::find($id);

    // 🛠 Valider les données envoyées
    // $utilisateur = UtilisateurModel::where('IdUt', $IdUt)->first();
    // if (!$utilisateur) {
    //     return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    // }
    // $request->validate([
    //     'nom' => 'string|max:255',
    //     'prenom' => 'string|max:255',
    //     'email' => 'email|max:255|unique:utilisateur,email,' . $IdUt,
    //     'password' => 'nullable|string|min:6',
    //     'role' => 'string'
    // ]);
    

    // // 📌 Mettre à jour les champs
    // $utilisateur->nom = $request->nom;
    // $utilisateur->prenom = $request->prenom;
    // $utilisateur->email = $request->email;
    // $utilisateur->role = $request->role;

    // // 🔑 Mettre à jour le mot de passe seulement s'il est fourni
    // if ($request->filled('password')) {
    //     $utilisateur->password = bcrypt($request->password);
    // }

    // // 📌 Sauvegarde dans la base de données
    // $utilisateur->save();

    // return response()->json(['message' => 'Utilisateur mis à jour avec succès'], 200);
        $utilisateur = UtilisateurModel::where('IdUt', $IdUt)->firstOrFail(); // Utiliser where() au lieu de find()

        $validatedData = $request->validate([
            'email' => 'required|email|unique:utilisateur,email,' . $IdUt . ',IdUt',
            'nom' => 'required|string',
            'prenom' => 'required|string',
        ]);

        $utilisateur->update($validatedData);

        return response()->json(['message' => 'Utilisateur mis à jour avec succès']);
        

    }
    public function destroy($id)
    {
        //$utilisateur = UtilisateurModel::find($id);
        $utilisateur = UtilisateurModel::where('IdUt', $id)->first();

        if (!$utilisateur) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.'
            ], 404);
        }

        $utilisateur->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.',
            'utilisateur' => $utilisateur
        ], 200);
    }

    public function countUsers()
{
    $count = UtilisateurModel::count(); // Compte le nombre total d'utilisateurs
    return response()->json(['count' => $count]);
}

}
