@extends('layouts.app')

@section('title', 'Página de Inicio')

@section('content')
    <div class="container">
        <h1 style="font-size: 2em; font-weight: bold; color: #333; margin-top: 20px;">Archivos Subidos</h1>

        <!-- Formulario de búsqueda -->
        <form action="/" method="GET" style="margin-bottom: 20px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Buscar archivos por nombre..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <!-- Navbar de Categorías -->
        <div class="file-navbar mt-4 mb-2">
            <button id="todos-archivos-tab" class="nav-btn active">Archivos de la comunidad</button>
            <button id="mis-archivos-tab" class="nav-btn">Mis Archivos</button>
            <button id="archivos-eliminados-tab" class="nav-btn">Archivos Eliminados</button>
        </div>

        <!-- Tablas de Archivos -->
        <div class="tab-content mt-3">
            <!-- Todos los Archivos -->
            <div class="tab-pane" id="todos-archivos" style="display: block;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Owner</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ficheros->where('privado', false) as $fichero)
                            <tr>
                                <td><a href="/archivo/{{ $fichero->id }}">{{ $fichero->name }}</a></td>
                                <td>{{ $fichero->size() }}</td>
                                <td>{{ $fichero->user->name }}</td>
                                <td>{{ $fichero->created_at }}</td>
                                <td>{{ $fichero->updated_at }}</td>
                                <td>
                                    <a href="/download/{{ $fichero->id }}" class="btn btn-primary">Descargar</a>
                                    @if($fichero->user_id == Auth::id())
                                        <a href="/delete/{{ $fichero->id }}" class="btn btn-danger">Eliminar</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mis Archivos -->
            <div class="tab-pane" id="mis-archivos" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Owner</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ficheros->where('user_id', Auth::id()) as $fichero)
                            <tr>
                                <td><a href="/archivo/{{ $fichero->id }}">{{ $fichero->name }}</a></td>
                                <td>{{ $fichero->size() }}</td>
                                <td>{{ $fichero->user->name }}</td>
                                <td>{{ $fichero->created_at }}</td>
                                <td>{{ $fichero->updated_at }}</td>
                                <td>
                                    <a href="/download/{{ $fichero->id }}" class="btn btn-primary">Descargar</a>
                                    <a href="/delete/{{ $fichero->id }}" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Archivos Eliminados -->
            <div class="tab-pane" id="archivos-eliminados" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Deleted At</th>
                            <th>Owner</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivosEliminados as $archivo)
                            <tr>
                                <td>{{ $archivo->name }}</td>
                                <td>{{ $archivo->deleted_at }}</td>
                                <td>{{ $archivo->user->name }}</td>
                                <td>
                                    <a href="/restore/{{ $archivo->id }}" class="btn btn-success">Restaurar</a>
                                    <a href="/force-delete/{{ $archivo->id }}" class="btn btn-danger">Eliminar Permanentemente</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const todosArchivosTab = document.getElementById('todos-archivos-tab');
            const misArchivosTab = document.getElementById('mis-archivos-tab');
            const archivosEliminadosTab = document.getElementById('archivos-eliminados-tab');
            const todosArchivosPane = document.getElementById('todos-archivos');
            const misArchivosPane = document.getElementById('mis-archivos');
            const archivosEliminadosPane = document.getElementById('archivos-eliminados');

            // Función para manejar el cambio de pestañas
            function switchTab(activeTab, inactiveTabs, activePane, inactivePanes) {
                activeTab.classList.add('active');
                inactiveTabs.forEach(tab => tab.classList.remove('active'));
                activePane.style.display = 'block';
                inactivePanes.forEach(pane => (pane.style.display = 'none'));
            }

            // Inicialmente mostrar "Todos los Archivos"
            switchTab(todosArchivosTab, [misArchivosTab, archivosEliminadosTab], todosArchivosPane, [misArchivosPane, archivosEliminadosPane]);

            // Event listener para las pestañas
            todosArchivosTab.addEventListener('click', function () {
                switchTab(todosArchivosTab, [misArchivosTab, archivosEliminadosTab], todosArchivosPane, [misArchivosPane, archivosEliminadosPane]);
            });

            misArchivosTab.addEventListener('click', function () {
                switchTab(misArchivosTab, [todosArchivosTab, archivosEliminadosTab], misArchivosPane, [todosArchivosPane, archivosEliminadosPane]);
            });

            archivosEliminadosTab.addEventListener('click', function () {
                switchTab(archivosEliminadosTab, [todosArchivosTab, misArchivosTab], archivosEliminadosPane, [todosArchivosPane, misArchivosPane]);
            });
        });
    </script>
@endsection
