<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\UtlisateurController;

// Routes pour les annonces
//utlisateurs n'est pas le nom de la table. C'est simplement une partie de l'URL utilisée pour accéder à cette route via une requête HTTP.

Route::get('/annonces', [AnnonceController::class, 'index']); // Récupérer toutes les annonces
Route::get('/annonces/create', [AnnonceController::class, 'create']); // Afficher le formulaire de création d'une annonce
Route::post('/annonces', [AnnonceController::class, 'store']); // Créer une nouvelle annonce
Route::get('/annonces/{id}', [AnnonceController::class, 'show']); // Afficher une annonce spécifique
Route::get('/annonces/{id}/edit', [AnnonceController::class, 'edit']); // Afficher le formulaire d'édition d'une annonce
Route::put('/annonces/{id}', [AnnonceController::class, 'update']); // Mettre à jour une annonce
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']); // Supprimer une annonce

// Routes pour les utilisateurs
Route::get('/utlisateurs', [UtlisateurController::class, 'index']); // Liste des utilisateurs
Route::post('/utlisateurs', [UtlisateurController::class, 'store']); // Ajouter un utilisateur
Route::get('/utlisateurs/{id}', [UtlisateurController::class, 'show']); // Détails d'un utilisateur
Route::put('/utlisateurs/{id}', [UtlisateurController::class, 'update']); // Modifier un utilisateur
Route::delete('/utlisateurs/{id}', [UtlisateurController::class, 'destroy']); // Supprimer un utilisateur

?>
