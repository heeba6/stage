<?php

namespace App\Http\Controllers;
use App\Models\CategorieModel;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    //Afficher la liste des catégories.
    public function index()
    {
        $categories = CategorieModel::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire pour créer une nouvelle catégorie.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie dans la base de données.
     */
    public function store(Request $request)
    {
        $request->validate([
            'IdCat' => 'required|unique:categorie,IdCat',
            'nomCat' => 'required|string|max:255',
        ]);

        CategorieModel::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Afficher une catégorie spécifique.
     */
    public function show($id)
    {
        $categorie = CategorieModel::findOrFail($id);
        return view('categories.show', compact('categorie'));
    }

    /**
     * Afficher le formulaire pour modifier une catégorie existante.
     */
    public function edit($id)
    {
        $categorie = CategorieModel::findOrFail($id);
        return view('categories.edit', compact('categorie'));
    }

    /**
     * Mettre à jour une catégorie existante.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomCat' => 'required|string|max:255',
        ]);

        $categorie = CategorieModel::findOrFail($id);
        $categorie->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie.
     */
    public function destroy($id)
    {
        $categorie = CategorieModel::findOrFail($id);
        $categorie->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }

}
