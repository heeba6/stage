<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtlisateurController extends Controller
{
    public function index()// Retrieve all users
    {
        $utlisateurs = UtlisateurModel::all();
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

        UtlisateurModel::create([
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
        $utlisateur = UtlisateurModel::findOrFail($id);
        return view('utlisateurs.show', compact('utlisateur'));
    }
    public function edit($id)
    {
        $utlisateur = UtlisateurModel::findOrFail($id);
        return view('utlisateurs.edit', compact('utlisateur'));
    }

}
