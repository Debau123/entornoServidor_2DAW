<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/create', [CommentController::class, 'create']);
Route::post('/comments', [CommentController::class, 'store']);
Route::get('/comments/{commentid}', [CommentController::class, 'show']);
Route::get('/comments/{commentid}/edit', [CommentController::class, 'edit']);
Route::patch('/comments/{commentid}', [CommentController::class, 'update']);
Route::delete('/comments/{commentid}', [CommentController::class, 'destroy']);




