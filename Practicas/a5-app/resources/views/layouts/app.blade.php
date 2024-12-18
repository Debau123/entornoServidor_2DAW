<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GITDAW')</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' viewBox='0 0 24 24'><path fill='currentColor' d='M23.546 10.93L13.067.452a1.55 1.55 0 0 0-2.188 0L8.708 2.627l2.76 2.76a1.838 1.838 0 0 1 2.327 2.341l2.658 2.66a1.838 1.838 0 0 1 1.9 3.039a1.837 1.837 0 0 1-2.6 0a1.85 1.85 0 0 1-.404-1.996L12.86 8.955v6.525c.176.086.342.203.488.348a1.85 1.85 0 0 1 0 2.6a1.844 1.844 0 0 1-2.609 0a1.834 1.834 0 0 1 0-2.598c.182-.18.387-.316.605-.406V8.835a1.834 1.834 0 0 1-.996-2.41L7.636 3.7L.45 10.881c-.6.605-.6 1.584 0 2.189l10.48 10.477a1.545 1.545 0 0 0 2.186 0l10.43-10.43a1.544 1.544 0 0 0 0-2.187'/></svg>" type="image/svg+xml">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        #app {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1; /* Empuja el footer hacia abajo */
        }
        footer {
            background-color: #333;
            color: white;
            padding: 1em;
            text-align: center;
            margin-top: auto; /* Asegura que el footer se mantenga al fondo */
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Incluir el navbar -->
        @include('layouts.navbar')

        <!-- Contenido de la página -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </div>
</body>
</html>
