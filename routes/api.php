<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\MessageController;


    // Routes pour les annonces (API)avec middleware
    // Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
    // Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store')->middleware('auth:sanctum'); // Supprimé csrf
    // Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
    // Route::put('/annonces/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update')->middleware('auth:sanctum'); // Supprimé csrf
    // Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy')->middleware('auth:sanctum'); // Supprimé csrf
    Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
    Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store');// Supprimé csrf
    Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
    Route::put('/annonces/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update'); // Supprimé csrf
    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy') ;// Supprimé csrf


    //Routes pour les utlisateurs (API)avec middleware
    Route::get('/utilisateurs', [UtilisateurController::class, 'index'])->name('utilisateurs.index');
    Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->name('utilisateurs.store')->middleware('auth:sanctum'); // Supprimé csrf
    Route::get('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'show'])->name('utilisateurs.show');
    Route::put('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'update'])->name('utilisateurs.update')->middleware('auth:sanctum'); // Supprimé csrf
    Route::delete('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'destroy'])->name('utilisateurs.destroy')->middleware('auth:sanctum'); // Supprimé csrf

    //Route::resource('utilisateurs', UtilisateurController::class);

    // Routes pour les catégories
    Route::get('/categories', [CategorieController::class, 'index']);
    Route::post('/categories', [CategorieController::class,'store']);
    Route::get('/categories/{id}', [CategorieController::class,'show']);
    Route::put('/categories/{id}', [CategorieController::class, 'update']);
    Route::delete('/categories/{id}', [CategorieController::class, 'destroy']);

    // Routes pour les favoris
    Route::get('/favoris', [FavorisController::class, 'index']);
    Route::post('/favoris', [FavorisController::class,'store']);
    Route::get('/favoris/{id}', [FavorisController::class,'destroy']);
    
    // Routes pour les messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class,'store']);
    Route::get('/messages/{id}', [MessageController::class,'destroy']);
    
    //Route::resource('categories', CategorieController::class);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
 


