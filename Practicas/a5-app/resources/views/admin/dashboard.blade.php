@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container mt-4">
    <h1>Panel de Administración</h1>
    <p class="text-muted">Gestión de usuarios y sus roles</p>

    <h2 class="mt-4">Usuarios</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role->name ?? 'Sin rol' }}</td>
                    <td>
                        <!-- Botón para editar usuarios -->
                        <a href="#" class="btn btn-primary btn-sm">Editar</a>
                        
                        <!-- Botón para eliminar usuarios -->
                        <form action="#" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
