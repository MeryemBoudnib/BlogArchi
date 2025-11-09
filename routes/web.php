<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route pour la page d'accueil simple
Route::get('/', function () {
    return view('welcome');
});


// GROUPE DE ROUTES PROTÉGÉES PAR LE MIDDLEWARE 'auth'
// Toutes les routes à l'intérieur ne sont accessibles qu'aux utilisateurs connectés.
Route::middleware('auth')->group(function () {
    
    // --- ROUTES POUR LE PROFIL UTILISATEUR (générées par Breeze) ---
    // C'est ce bloc qui va corriger votre erreur "profile.edit not defined"
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- VOS ROUTES POUR LE CRUD DES POSTS ---
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});


// Ce fichier contient toutes les routes pour l'authentification (login, register, logout...)
require __DIR__.'/auth.php';