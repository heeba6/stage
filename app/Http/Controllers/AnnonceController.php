<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index()// Retrieve all announcements
    {
        $annonces = annonce::all(); //méthode statique du modèle Eloquent Annonce
        return view('annonces.index', compact('annonces')); // Pass annonces to a view
    }
    public function create()
    {
        return view('annonces.create');// Show the form for creating a new announcement
    }

    public function store(Request $request)// Store a newly created announcement
    {
        $request->validate([
           'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'adressse' => 'required|string',
            'type' => 'required|string',
            'datePub' => 'required|date',
            'etat' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional photo upload validation
        ]);

        $photoPath = null;
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photoPath = $request->file('photo')->store('photos', 'public'); // Store photo in the 'public/photos' folder
        }

        Annonce::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->prix,
            'adressse' => $request->adressse,
            'type' => $request->type,
            'datePub' => $request->datePub,
            'etat' => $request->etat,
            'IdUt' => auth()->id(), // Assuming the logged-in user is the owner of the annonce
            'photo' => $photoPath, // Save the photo path if uploaded
        ]);
        return redirect()->route('annonces.index')
                         ->with('success', 'Annonce created successfully.');
    }
    public function show($id)// Display the specified announcement
    {
        $annonce = Annonce::findOrFail($id); // Find the announcement by ID
        return view('annonces.show', compact('annonce'));
    }
    public function edit($id)
    {
        $annonce = Annonce::findOrFail($id); // Retrieve the announcement
        return view('annonces.edit', compact('annonce'));
    }

    public function update(Request $request, $id)
    {
        // Validate input fields
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'adressse' => 'required|string',
            'type' => 'required|string',
            'datePub' => 'required|date',
            'etat' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional photo upload validation
        ]);
        $annonce = Annonce::findOrFail($id); // Retrieve the announcement

        // Handle the photo upload if exists
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            // Delete the old photo if exists
            if ($annonce->photo && file_exists(storage_path('app/public/' . $annonce->photo))) {
                unlink(storage_path('app/public/' . $annonce->photo));
            }
            $photoPath = $request->file('photo')->store('photos', 'public'); // Store the new photo
        } else {
            $photoPath = $annonce->photo; // Keep the existing photo if no new one is uploaded
        }

        // Update the announcement
        $annonce->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->prix,
            'adressse' => $request->adressse,
            'type' => $request->type,
            'datePub' => $request->datePub,
            'etat' => $request->etat,
            'photo' => $photoPath, // Save the updated photo path
        ]);

        return redirect()->route('annonces.index')
                         ->with('success', 'Annonce updated successfully.');
    }

    // Remove the specified announcement from storage
    public function destroy($id)
    {
        $annonce = Annonce::findOrFail($id);

        // Delete the photo if exists
        if ($annonce->photo && file_exists(storage_path('app/public/' . $annonce->photo))) {
            unlink(storage_path('app/public/' . $annonce->photo));
        }

        // Delete the announcement
        $annonce->delete();

        return redirect()->route('annonces.index')
                         ->with('success', 'Annonce deleted successfully.');
    }

}
