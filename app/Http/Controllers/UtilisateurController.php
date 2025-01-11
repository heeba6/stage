<?php

namespace App\Http\Controllers;
use App\Models\UtilisateurModel;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()// Retrieve all users
    {
        $utlisateurs = UtilisateurModel::all();
        return view('utlisateurs.index', compact('utlisateurs'));
    }
    public function create()
    {
        return view('utlisateurs.create');// Show the form for creating a new announcement
    }
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utlisateur,email',
            'mtP' => 'required|string|min:6',
            'role' => 'required|string|in:user,admin', // Par exemple, les rôles autorisés
        ]);

        UtilisateurModel::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'mtP' => bcrypt($request->mtP), // Hash du mot de passe
            'role' => $request->role,
        ]);

        return redirect()->route('utlisateurs.index')->with('success', 'Utilisateur créé avec succès.');
    }
    public function show($id)
    {
        // $utlisateur = UtilisateurModel::findOrFail($id);
        // return view('utlisateurs.show', compact('utlisateur'));
        $utilisateur = UtilisateurModel::find($id);
        //dd($utilisateur);

        if (!$utilisateur) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404); // Retourner une erreur si l'utilisateur n'est pas trouvé
        }

        return response()->json($utilisateur);
    }
    public function edit($id)
    {
        $utlisateur = UtilisateurModel::findOrFail($id);
        return view('utlisateurs.edit', compact('utlisateur'));
    }

}
