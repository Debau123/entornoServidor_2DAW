<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Enviar Nueva Noticia</title>
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
            .card {
                background-color: #ffffff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
            }
            .card-header {
                background-color: #1e40af;
                color: #ffffff;
                padding: 15px;
                border-radius: 10px 10px 0 0;
                font-size: 1.5rem;
                text-align: center;
            }
            .card-body {
                padding: 20px;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-label {
                font-weight: bold;
                margin-bottom: 5px;
                display: block;
            }
            .form-control {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            .btn {
                background-color: #28a745;
                color: #ffffff;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
            }
            .btn:hover {
                background-color: #218838;
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
            <div class="card">
                <div class="card-header">
                    Enviar Nueva Noticia
                </div>
                <div class="card-body">
                    <form method="POST" action="/store">
                        @csrf
                        <div class="form-group">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Escribe el título de la noticia" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cuerpo" class="form-label">Noticia</label>
                            <textarea name="cuerpo" id="cuerpo" rows="4" class="form-control" placeholder="Escribe el contenido de la noticia" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="enlace" class="form-label">Enlace</label>
                            <input type="url" name="enlace" id="enlace" class="form-control" placeholder="https://ejemplo.com">
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
            <a href="{{ url('/') }}" class="back-button">Volver al Noticiario</a>
        </div>
    </body>
</html>
