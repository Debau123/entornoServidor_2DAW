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
            <button id="archivos-compartidos-tab" class="nav-btn">Archivos Compartidos</button>
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
                                    @if(Storage::exists($fichero->path))
                                        <a href="/download/{{ $fichero->id }}" class="btn btn-primary">Descargar</a>
                                    @else
                                        <button class="btn btn-secondary" disabled>No disponible</button>
                                    @endif
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
                            <th>Compartir</th>
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
                                    @if(Storage::exists($fichero->path))
                                        <a href="/download/{{ $fichero->id }}" class="btn btn-primary">Descargar</a>
                                    @else
                                        <button class="btn btn-secondary" disabled>No disponible</button>
                                    @endif
                                    <a href="/delete/{{ $fichero->id }}" class="btn btn-danger">Eliminar</a>
                                </td>
                                <td>
                                    <form action="{{ route('compartir.archivo', ['id' => $fichero->id]) }}" method="POST">
                                        @csrf
                                        <select name="user_id" required>
                                            <option value="" disabled selected>Seleccionar usuario</option>
                                            @foreach($usuarios as $usuario)
                                                @if($usuario->id !== Auth::id() && !$fichero->sharedUsers->contains($usuario->id))
                                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-success">Compartir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Archivos Compartidos -->
            <div class="tab-pane" id="archivos-compartidos" style="display: none;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Owner</th>
                            <th>Shared At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivosCompartidos as $archivo)
                            <tr>
                                <td><a href="/archivo/{{ $archivo->id }}">{{ $archivo->name }}</a></td>
                                <td>{{ $archivo->owner_name }}</td>
                                <td>{{ $archivo->shared_at }}</td>
                                <td>
                                    @if(Storage::exists($archivo->path))
                                        <a href="/download/{{ $archivo->id }}" class="btn btn-primary">Descargar</a>
                                    @else
                                        <button class="btn btn-secondary" disabled>No disponible</button>
                                    @endif
                                    <form action="{{ route('eliminar.compartido', ['id' => $archivo->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Archivos Eliminados -->
            <div class="tab-pane" id="archivos-eliminados" style="display: none;">
                @php
                    $archivosEliminadosUsuario = $archivosEliminados->where('user_id', Auth::id());
                @endphp

                @if($archivosEliminadosUsuario->isNotEmpty())
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
                            @foreach($archivosEliminadosUsuario as $archivo)
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
                @else
                    <p>No tienes archivos eliminados.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer style="background-color: #333; color: white; text-align: center; padding: 10px; margin-top: auto;">
        iñaki borrego bau 2024
    </footer>
@endsection

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = {
                'todos-archivos-tab': 'todos-archivos',
                'mis-archivos-tab': 'mis-archivos',
                'archivos-compartidos-tab': 'archivos-compartidos',
                'archivos-eliminados-tab': 'archivos-eliminados',
            };

            Object.keys(tabs).forEach(tabId => {
                const tab = document.getElementById(tabId);
                tab.addEventListener('click', function () {
                    Object.keys(tabs).forEach(otherTabId => {
                        const otherTab = document.getElementById(otherTabId);
                        const otherPane = document.getElementById(tabs[otherTabId]);
                        if (tabId === otherTabId) {
                            otherTab.classList.add('active');
                            otherPane.style.display = 'block';
                        } else {
                            otherTab.classList.remove('active');
                            otherPane.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
