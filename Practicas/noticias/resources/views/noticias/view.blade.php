
@extends('layouts.app')

@section('content')
    <h1>{{ $noticia->titulo }}</h1>
    <p>{{ $noticia->cuerpo }}</p>
    <a href="{{ route('noticias.index') }}">Volver a las noticias</a>
@endsection