<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/test-route', function () {
    return 'Test route fonctionne';
});
