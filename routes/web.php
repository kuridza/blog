<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', [PostController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('posts', PostController::class);

Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
Route::resource('comments', CommentController::class)->except('show');

Route::post('comments/{comment}/flag', [CommentController::class, 'flag'])->name('comments.flag')->middleware('auth');
Route::delete('comments/{comment}/flag', [CommentController::class, 'unflag'])->name('comments.unflag')->middleware('auth');

require __DIR__.'/auth.php';
