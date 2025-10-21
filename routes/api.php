<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostApiController;

Route::get('posts', [PostApiController::class, 'index']);
Route::get('stats', [PostApiController::class, 'stats']);
