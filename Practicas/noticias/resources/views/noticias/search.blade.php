
@extends('layouts.app')

@section('content')
    <h1>Resultados de la búsqueda: "{{ $term }}"</h1>
    @foreach ($noticias as $noticia)
        <div class="noticia">
            <h2>{{ $noticia->titulo }}</h2>
            <p>{{ $noticia->resumen }}</p>
            <a href="{{ route('noticias.view', $noticia->id) }}">Leer más</a>
        </div>
    @endforeach

    {{ $noticias->links() }}
@endsection