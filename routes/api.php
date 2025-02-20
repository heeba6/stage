<?php
// use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\UtilisateurModel; // Assurez-vous que UtilisateurModel est importé
use App\Models\AnnonceModel; // Assurez-vous que AnnonceModel est importé
use App\Models\FavorisModel; // Assurez-vous que FavorisModel est importé
use App\Models\MessageModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TestController;

Route::get('/test-email', [TestController::class, 'sendTestEmail']);

Route::post('forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
Route::post('/complete-reset-password', [AuthController::class, 'completeResetPassword']);

// Routes utilisant les contrôleurs pour l'enregistrement et la connexion
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

Route::post('/login', [AuthController::class, 'login']);


// Routes pour les annonces (API)

Route::get('/annonces', [AnnonceController::class, 'index'])->name('annonces.index');
Route::post('/annonces', [AnnonceController::class, 'store'])->name('annonces.store')->middleware('auth:sanctum');
Route::get('/annonces/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
Route::put('/annonces/{annonce}', [AnnonceController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy')->middleware('auth:sanctum');
Route::get('/annonces/count', [AnnonceController::class, 'countAnnonces'])->middleware('auth:sanctum');
Route::get('/annonces/utilisateur', [AnnonceController::class, 'getAnnoncesUtilisateur'])->middleware('auth:sanctum');

// Routes pour les utilisateurs (API)
Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
Route::post('/utilisateurs', [UtilisateurController::class, 'store'])->middleware('auth:sanctum');
Route::get('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'show']);
Route::put('/utilisateurs/{IdUt}', [UtilisateurController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/utilisateurs/{utilisateur}', [UtilisateurController::class, 'destroy'])->middleware('auth:sanctum');
Route::get('/utilisateurs/count', [UtilisateurController::class, 'countUsers'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Ajouter un favori
    Route::post('/favoris', [FavorisController::class, 'addToFavoris']);

    // Supprimer un favori
    Route::delete('/favoris/{annonceId}', [FavorisController::class, 'removeFromFavoris']);
        Route::post('/favoris', [FavorisController::class, 'store']);
    // Lister les favoris
    Route::get('/favoris', [FavorisController::class, 'index']);
    // Supprimer un favori spécifique
    Route::delete('/favoris/{favoriId}', [FavorisController::class, 'destroy'])->name('favoris.destroy');
    // Vérifier si un élément est dans les favoris
    Route::get('/favoris/check', [FavorisController::class, 'check']);
    // Supprimer tous les favoris
    Route::delete('/favoris', [FavorisController::class, 'clear'])->name('favoris.clear');    
});
Route::middleware('auth:sanctum')->get('/favoris', [FavorisController::class, 'getUserFavoris']);

// Route pour récupérer les informations de l'utilisateur authentifié
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('messages')->group(function () {
    // Récupérer les messages d'une annonce
    Route::get('/annonce/{annonceId}', [MessageController::class, 'index']);

    // Ajouter un message
    Route::post('/', [MessageController::class, 'store']);

    // Supprimer un message
    Route::delete('/{id}', [MessageController::class, 'destroy']);
});
