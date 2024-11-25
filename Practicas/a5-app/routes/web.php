<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Policies\AdminPolicy;
use App\Policies\UserPolicy;

// Ruta principal con búsqueda de archivos
Route::get('/', [FileController::class, 'index']);

// Para eliminar archivos compartidos
Route::delete('/eliminar-compartido/{id}', [FileController::class, 'deleteShared'])
    ->name('eliminar.compartido')
    ->middleware('auth');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

// Logout
Route::get('/logout', [AuthController::class, 'logout']);

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);

// Subida de archivos
Route::get('/subir-archivos', [FileController::class, 'showUploadForm'])
    ->middleware('auth');

// Rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/download/{file}', [FileController::class, 'download'])->name('download');
    Route::get('/delete/{file}', [FileController::class, 'softDelete']);
    Route::get('/restore/{id}', [FileController::class, 'restore'])->name('restore');
    Route::get('/force-delete/{id}', [FileController::class, 'forceDelete'])->name('force-delete');
});

// Vista individual del archivo
Route::get('/archivo/{id}', [FileController::class, 'show'])
    ->name('archivo.view');

// Compartir archivos
Route::post('/compartir/{id}', [FileController::class, 'share'])
    ->name('compartir.archivo')
    ->middleware('auth');

// Actualización de archivos
Route::put('/archivo/{id}', [FileController::class, 'update'])
    ->name('archivo.update');

// Ver PDF
Route::get('/ver-pdf/{id}', [FileController::class, 'viewPdf'])
    ->name('ver.pdf');

// Panel de administración
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
->can('accessAdminPanel', User::class)
->name('admin.dashboard');
