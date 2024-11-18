@extends('layouts.app')

@section('content')
    <h1>Noticias</h1>
    @foreach ($noticias as $noticia)
        <div class="noticia">
            <h2>{{ $noticia->titulo }}</h2>
            <p>{{ $noticia->resumen }}</p>
            <a href="{{ route('noticias.view', $noticia->id) }}">Leer más</a>
        </div>
    @endforeach

    <div>
        @if ($noticias->currentPage() > 1)
            <a href="{{ $noticias->previousPageUrl() }}">Anterior</a>
        @endif

        Página {{ $noticias->currentPage() }} de {{ $noticias->lastPage() }}

        @if ($noticias->hasMorePages())
            <a href="{{ $noticias->nextPageUrl() }}">Siguiente</a>
        @endif
    </div>
@endsection
