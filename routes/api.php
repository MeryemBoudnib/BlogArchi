<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route de connexion pour obtenir un jeton (publique)
Route::post('/login', [AuthController::class, 'login']);

// Groupe de routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Route pour récupérer la liste des posts
    Route::get('/posts', [PostController::class, 'index']);

    // Route pour récupérer l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post('/login', [AuthController::class, 'login']);
