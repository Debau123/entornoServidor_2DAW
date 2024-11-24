<div style="background-color: orange; display: flex; justify-content: space-between; align-items: center; padding: 1em;">
    <!-- Título de la página -->
    <div style="flex-grow: 1; text-align: center;">
        <h1 style="margin: 0; font-size: 1.5em; color: white;">GITDAW</h1>
    </div>

    <!-- Botones de autenticación -->
    <div>
        @auth
            <a href="/subir-archivos" style="text-decoration: none; color: white; background-color: #007bff; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">Subir Archivos</a>

            <!-- Botón de Administración (Solo visible para Admins) -->
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: white; background-color: #ffc107; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">
                    Administración
                </a>
            @endif

            <a href="/logout" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px;">Logout</a>
        @else
            <a href="/login" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px; margin-right: 0.5em;">Login</a>
            <a href="/register" style="text-decoration: none; color: white; background-color: #333; padding: 0.5em 1em; border-radius: 5px;">Register</a>
        @endauth
    </div>
</div>
