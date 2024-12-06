@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container mt-4">
    <div class="text-center mb-4">
        <h1 style="color: black;">Panel de Administración</h1>
        <p class="text-muted">Gestión de usuarios, roles y archivos</p>
    </div>

    <!-- Botones para alternar entre tablas -->
    <div class="d-flex justify-content-center mb-3">
        <button id="show-users" class="btn btn-primary me-2">Usuarios</button>
        <button id="show-files" class="btn btn-secondary">Archivos</button>
    </div>

    <!-- Tabla de Usuarios -->
    <div id="users-table" style="display: block;">
        <div class="card bg-dark text-light">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Usuarios</h2>
            </div>
            <div class="card-body">
                <table class="table table-dark table-hover">
                    <thead class="table-primary text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Contraseña</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr id="user-row-{{ $user->id }}">
                                <form action="{{ route('admin.user.update', $user->id) }}" method="POST" id="form-{{ $user->id }}">
                                    @csrf
                                    @method('PUT')
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <input type="text" name="name" id="name-{{ $user->id }}" value="{{ $user->name }}" class="form-control form-control-sm" readonly>
                                    </td>
                                    <td>
                                        <input type="email" name="email" id="email-{{ $user->id }}" value="{{ $user->email }}" class="form-control form-control-sm" readonly>
                                    </td>
                                    <td>
                                        <select name="role_id" id="role-{{ $user->id }}" class="form-control form-control-sm" disabled>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="password" name="password" id="password-{{ $user->id }}" class="form-control form-control-sm" placeholder="Nueva contraseña" readonly>
                                    </td>
                                    <td>
                                        <!-- Botón para habilitar edición -->
                                        <button type="button" class="btn btn-primary btn-sm" onclick="enableEditing({{ $user->id }})" id="edit-btn-{{ $user->id }}">Modificar</button>
                                        <!-- Botón para guardar cambios -->
                                        <button type="submit" class="btn btn-success btn-sm" style="display:none;" id="save-btn-{{ $user->id }}">Guardar</button>
                                        <!-- Botón de eliminar usuario -->
                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabla de Archivos -->
    <div id="files-table" style="display: none;">
        <div class="card bg-dark text-light">
            <div class="card-header bg-secondary text-white">
                <h2 class="mb-0">Archivos</h2>
            </div>
            <div class="card-body">
                <table class="table table-dark table-hover">
                    <thead class="table-secondary text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tamaño</th>
                            <th>Propietario</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td>{{ $file->id }}</td>
                                <td>{{ $file->name }}</td>
                                <td>{{ $file->size() }}</td>
                                <td>{{ $file->user ? $file->user->name : 'Usuario eliminado' }}</td>
                                <td>{{ $file->created_at }}</td>
                                <td>
                                    <form action="{{ route('admin.file.destroy', $file->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este archivo?')">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/Ocultar tablas
    document.getElementById('show-users').addEventListener('click', function() {
        document.getElementById('users-table').style.display = 'block';
        document.getElementById('files-table').style.display = 'none';
    });

    document.getElementById('show-files').addEventListener('click', function() {
        document.getElementById('users-table').style.display = 'none';
        document.getElementById('files-table').style.display = 'block';
    });

    // Habilitar edición en filas de usuarios
    function enableEditing(userId) {
        const row = document.getElementById(`user-row-${userId}`);
        const inputs = row.querySelectorAll('input, select');

        // Habilitar edición
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            input.removeAttribute('disabled');
        });

        // Mostrar botón Guardar y ocultar botón Modificar
        document.getElementById(`edit-btn-${userId}`).style.display = 'none';
        document.getElementById(`save-btn-${userId}`).style.display = 'inline-block';
    }
</script>
@endsection
