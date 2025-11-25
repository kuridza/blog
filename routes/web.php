<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('ads.index'));
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/create', [ProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [AdController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
});

Route::resource('ads', AdController::class);

Route::resource('categories', CategoryController::class)->middleware('auth');

require __DIR__.'/auth.php';
