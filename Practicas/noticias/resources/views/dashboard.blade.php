<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mis Noticias</title>
        <style>
            body {
                background-color: #f0f0f0;
                font-family: Arial, sans-serif;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                background-color: #ff8c00;
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 20px;
            }
            header h1 {
                color: #ffffff;
                font-size: 2.5rem;
                margin: 0;
            }
            nav a {
                color: #ffffff;
                text-decoration: none;
                margin-left: 15px;
                font-weight: bold;
            }
            nav a:hover {
                text-decoration: underline;
            }
            .noticia-card {
                background-color: #ffffff;
                padding: 20px;
                margin-bottom: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .noticia-card h2 {
                font-size: 1.75rem;
                color: #1e40af;
            }
            .noticia-card p {
                color: #555;
                margin-top: 10px;
            }
            .votos {
                display: flex;
                align-items: center;
                margin-top: 15px;
            }
            .votos button {
                background: none;
                border: none;
                font-size: 2rem;
                cursor: pointer;
                margin-right: 10px;
            }
            .votos button:hover {
                transform: scale(1.1);
            }
            .votos span {
                font-size: 1rem;
                color: #333;
            }
            .back-button {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: #1e40af;
                color: #ffffff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: bold;
            }
            .back-button:hover {
                background-color: #163a9f;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Mis Noticias</h1>
                <nav>
                    @auth
                        <a href="{{ route('dashboard') }}">Mis Noticias</a>
                        <a href="{{ url('/enviar') }}">Enviar Noticias</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}">Iniciar Sesión</a>
                        <a href="{{ route('register') }}">Registrarse</a>
                    @endauth
                </nav>
            </header>
            <div class="noticias">
                @if($noticias->isNotEmpty())
                @foreach ($noticias as $noticia)
                    <div class="noticia-card">
                        <a href="{{ route('noticias.show', ['id' => $noticia->id]) }}">
                            <h2>{{ $noticia->titulo }}</h2>
                        </a>
                        <p>{{ $noticia->cuerpo }}</p>
                        <div class="votos">
                            <form action="{{ route('noticias.votar', ['id' => $noticia->id]) }}" method="POST" class="mr-4">
                                @csrf
                                <button type="submit" name="tipo_voto" value="like" style="color: green;">
                                    👍
                                </button>
                            </form>
                            <form action="{{ route('noticias.votar', ['id' => $noticia->id]) }}" method="POST">
                                @csrf
                                <button type="submit" name="tipo_voto" value="dislike" style="color: red;">
                                    👎
                                </button>
                                <imput name= "noticia_id" type="hidden" value= "{{$noticia ->id}}"/>
                            </form>
                            <span>Votos: {{ $noticia->votos_count }}</span>
                        </div>
                    </div>
                @endforeach
                @else
                    <div class="text-center">
                        <p>No hay noticias disponibles en este momento.</p>
                    </div>
                @endif
                <a href="{{ url('/') }}" class="back-button">Volver al Noticiario</a>
            </div>
        </div>
    </body>
</html>