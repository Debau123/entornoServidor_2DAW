<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\UpdateNoticiaRequest;
use App\Models\Noticia;
use App\Models\Voto; // Importación del modelo Voto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticiaController extends Controller
{
    public function index()
    {
        $noticias = Noticia::paginate(15);
        return view('welcome', compact('noticias'));
    }

    public function view($id)
    {
        
        $noticia = Noticia::findOrFail($id);
        return view('views', compact('noticia'));
    }

    public function votar()
    {




    }

    

    
}

