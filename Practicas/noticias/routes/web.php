<?php

use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ProfileController;
use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\StoreVotoRequest;
use App\Models\Noticia;
use App\Models\Voto;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Ruta para la página principal donde se muestran todas las noticias
Route::get('/', [NoticiaController::class, 'index'])->name('noticias.index');

// Ruta para enviar una nueva noticia (requiere autenticación)
Route::get('/enviar', function () {
    return view('enviar');
})->middleware('auth')->name('enviar');

// Ruta para almacenar una nueva noticia (requiere autenticación)
Route::post('/store', function (StoreNoticiaRequest $storeNoticiaRequest) {
    $noticia = new Noticia;
    $noticia->fill($storeNoticiaRequest->validated());
    $noticia->user_id = Auth::id();
    $noticia->save();
    return redirect()->route('noticias.index');
})->middleware('auth');

// Ruta para mostrar las noticias del usuario autenticado (Mis Noticias)
Route::get('/dashboard', function () {
    return view('dashboard')->with('noticias', Auth::user()->noticias);
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas relacionadas al perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ruta para ver una noticia específica (sin requerir autenticación)
Route::get('/noticias/{id}', [NoticiaController::class, 'view'])->name('noticias.show');

// Ruta para votar una noticia (requiere autenticación)
Route::post('/noticias/{id}/votar', [NoticiaController::class, 'votar'])->name('noticias.votar')->middleware('auth');

Route::post('/votar', function (StoreVotoRequest $storeVotoRequest) {
    $storeVotoRequest ->validate();
    $voto = new Voto();
    $voto->noticia_id = $storeVotoRequest->noticia_id;
    $voto->user_id =Auth::user()->id ;
    $voto->save();
    return view('/');
});

// Rutas de autenticación generadas por Laravel Breeze o cualquier otro paquete
require __DIR__ . '/auth.php';
