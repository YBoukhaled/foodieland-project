<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;

// ── Public routes ──────────────────────────────
Route::post('/admin/login', [LoginController::class, 'login']);
Route::get('/categories',   [CategoryController::class, 'index']);
Route::get('/recipes',      [RecipeController::class, 'index']);
Route::get('/recipes/{id}', [RecipeController::class, 'show']);

// ── Protected routes (JWT) ─────────────────────
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/user',    [LoginController::class, 'me']);

    // Only store/update/delete need auth — index and show are public above
    Route::post('/recipes',         [RecipeController::class, 'store']);
    Route::put('/recipes/{id}',     [RecipeController::class, 'update']);
    Route::delete('/recipes/{id}',  [RecipeController::class, 'destroy']);
});
