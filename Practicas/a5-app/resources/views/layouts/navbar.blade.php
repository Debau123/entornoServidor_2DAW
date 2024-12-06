<div style="background-color: orange; display: flex; justify-content: space-between; align-items: center; padding: 1em; position: relative;">
    <!-- Nombre del usuario logeado -->
    <div style="position: absolute; left: 1em; color: white; font-size: 1.2em;">
        @auth
            Bienvenido, {{ Auth::user()->name }}
        @else
            Invitado
        @endauth
    </div>

    <!-- Título de la página -->
    <div style="flex-grow: 1; text-align: center;">
        <h1 style="margin: 0; font-size: 1.5em; color: white;">GITDAW</h1>
    </div>

    <!-- Botones de autenticación -->
    <div>
        <a href="/" style="text-decoration: none; color: white; background-color: #28a745; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">
            Inicio
        </a>
        @auth
            <a href="/subir-archivos" style="text-decoration: none; color: white; background-color: #007bff; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">
                Subir Archivos
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: white; background-color: #ffc107; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">
                    Administración
                </a>
            @endif
            <a href="/logout" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px;">
                Logout
            </a>
        @else
            <a href="/login" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">
                Login
            </a>
            <a href="/register" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px;">
                Register
            </a>
        @endauth
    </div>
</div>
