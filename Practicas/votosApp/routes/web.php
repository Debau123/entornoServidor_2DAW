<?php

use App\Http\Controllers\ProfileController;
use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\StoreVotoRequest;
use App\Http\Requests\StoreComentarioRequest;
use App\Models\Comentario;
use App\Models\Noticia;
use App\Models\Voto;
use Illuminate\Support\Facades\Auth ;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome')-> with('noticias', Noticia::all());
});

Route::get('/enviar', function () {
    return view('enviar');
});

Route::post('/store', function(StoreNoticiaRequest $storeNoticiaRequest){

    $noticia = new Noticia;

    $noticia->fill($storeNoticiaRequest -> validated());
    $noticia->user_id = Auth::id();

    $noticia -> save();

    return redirect('/');
});

Route::get('/noticia/{noticia}', function(Noticia $noticia){

    return view ('show') ->with('noticia', $noticia);

});

Route::post('/votar', function (StoreVotoRequest $storeVotoRequest) {
    $storeVotoRequest->validated();
    $voto = new Voto;
    $voto->noticia_id = $storeVotoRequest->noticia_id;
    $voto->user_id = Auth::user()->id;
    $voto->save();
    return redirect('/');
});


// breeze
Route::get('/dashboard', function () {

    return view('dashboard')->with('noticias', Auth::user()->noticias);

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('/comentar', function (StoreComentarioRequest $storeComentarioRequest) {
    $storeComentarioRequest->validated();
    $comentario = new Comentario();
    $comentario->noticia_id = $storeComentarioRequest->noticia_id;
    $comentario->user_id = Auth::user()->id;
    $comentario->text = $storeComentarioRequest->text;
    $comentario->save();
    return redirect()->back();
});

require __DIR__.'/auth.php';
