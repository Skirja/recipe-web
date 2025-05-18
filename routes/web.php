<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RecipeLikeController;
use App\Http\Controllers\HomeController; // Added

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Routes for creating, storing, editing, updating, deleting recipes (require authentication)
    Route::resource('recipes', RecipeController::class)->except(['index', 'show']);
    // It's also common to define a route for user's own recipes, e.g., /my-recipes
    // For now, RecipeController's index might be used for all recipes, or filtered later.

    // Route for storing comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

    // Routes for liking/unliking recipes
    Route::post('/recipes/{recipe}/like', [RecipeLikeController::class, 'store'])->name('recipes.like');
    Route::delete('/recipes/{recipe}/like', [RecipeLikeController::class, 'destroy'])->name('recipes.unlike');

    // Route for user's own recipes
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my');
});

// Publicly accessible recipe routes
Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
// Note: The {recipe} parameter will use route model binding. Ensure slug or ID is used consistently.
// If using slugs, the Recipe model should have a getRouteKeyName() method returning 'slug'.
