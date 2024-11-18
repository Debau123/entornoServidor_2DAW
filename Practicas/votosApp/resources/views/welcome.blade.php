<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
       
    @auth
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        <a href="{{ url('/enviar') }}">Enviar noticia</a>
    @else
        <a href="{{ route('login') }}">Log in </a>

        @if (Route::has('register'))

            <a href="{{ route('register') }}">Register</a>

        @endif
    @endauth
    <main>
        <ul>
            @foreach ($noticias as $noticia)
            <li>
                <form method="POST" action="/votar">
                    @csrf
                    <input type="submit" value="votar" id="botonvotar">
                    <input name="noticia_id" type="hidden"  value="{{ $noticia->id }}" >
                </form>
                <a href="{{ $noticia->enlace }}">{{ $noticia->titulo }}</a>
                <p>
                    {{ count($noticia ->votos) }} votos |
                    {{ $noticia->user->name }} | <a href="/noticia/{{ $noticia->id }}">{{ count($noticia->comentarios)}} Comentarios</a></p>
            </li>   
            @endforeach
        </ul>
    </main>
    </body>
</html>
