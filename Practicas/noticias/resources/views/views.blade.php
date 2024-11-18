<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $noticia->titulo }}</title>
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
                margin-bottom: 20px;
            }
            header h1 {
                color: #1e40af;
                font-size: 2.5rem;
                margin: 0;
            }
            header p {
                color: #555;
                margin-top: 10px;
            }
            main p {
                color: #333;
                font-size: 1.125rem;
                margin-top: 20px;
            }
            main a {
                color: #1e40af;
                text-decoration: none;
            }
            main a:hover {
                text-decoration: underline;
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
                <h1>{{ $noticia->titulo }}</h1>
                <p>Publicado por: {{ $noticia->user->name ?? 'Anónimo' }}</p>
            </header>
            <main>
                <p>{{ $noticia->cuerpo }}</p>
                <p>Enlace: <a href="{{ $noticia->enlace }}" target="_blank" rel="noopener noreferrer">{{ $noticia->enlace }}</a></p>
            </main>
            <a href="{{ url('/') }}" class="back-button">Volver al Noticiario</a>
        </div>
    </body>
</html>
