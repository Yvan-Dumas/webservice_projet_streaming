<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusiqueController;
use App\Http\Controllers\PlaylistController;

// --- Routes publiques

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/musiques', [MusiqueController::class, 'index']);


// --- Routes privées

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/playlists', [PlaylistController::class, 'index'])->middleware('auth:sanctum');
Route::post('/playlists', [PlaylistController::class, 'store'])->middleware('auth:sanctum');
Route::post('/playlists/{playlist}/musiques', [PlaylistController::class, 'ajouterMusique'])->middleware('auth:sanctum');

Route::post('/musiques/{musique}/acheter', [MusiqueController::class, 'acheter'])->middleware('auth:sanctum');;
