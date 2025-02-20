<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Route pour la vérification d'email
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);

// Route pour l'inscription
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
