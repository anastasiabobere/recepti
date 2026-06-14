<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TranslationController;

Route::get('/receptes/{recipe}/translate', [TranslationController::class, 'translate'])->name('recipes.translate');

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
// ── Public routes ────────────────────────────────────────────────────────────
Route::get('/', [RecipeController::class, 'index'])->name('recipes.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/receptes/create', [RecipeController::class, 'create'])->name('recipes.create');
});

Route::get('/receptes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');

Route::get('/lietotaji/{user}', [UserController::class, 'show'])->name('users.show');

// ── Auth routes ───────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Authenticated user routes ─────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/profils', [UserController::class, 'profile'])->name('profile');

    Route::post('/lietotaji/{user}/follow', [UserController::class, 'follow'])->name('users.follow');
    Route::delete('/lietotaji/{user}/follow', [UserController::class, 'unfollow'])->name('users.unfollow');
    Route::post('/receptes/{recipe}/save', [UserController::class, 'toggleSave'])->name('recipes.toggle-save');

    Route::post('/receptes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/receptes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/receptes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/receptes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

    Route::post('/receptes/{recipe}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::get('/receptes', [AdminController::class, 'recipes'])->name('recipes');
    Route::delete('/receptes/{recipe}', [AdminController::class, 'deleteRecipe'])->name('recipes.delete');
    Route::patch('/receptes/{recipe}/toggle-publish', [AdminController::class, 'togglePublish'])->name('recipes.toggle-publish');

    Route::get('/lietotaji', [AdminController::class, 'users'])->name('users');
    Route::patch('/lietotaji/{user}/toggle-block', [AdminController::class, 'toggleBlock'])->name('users.toggle-block');

    Route::get('/komentari', [AdminController::class, 'comments'])->name('comments');
    Route::delete('/komentari/{comment}', [AdminController::class, 'deleteComment'])->name('comments.delete');

    Route::get('/kategorijas', [AdminController::class, 'categories'])->name('categories');
    Route::post('/kategorijas', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::delete('/kategorijas/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
});