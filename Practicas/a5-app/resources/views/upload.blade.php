@extends('layouts.app')

@section('title', 'Subir Archivos')

@section('content')
    <div class="container">
        <h2>Subir Archivos</h2>
        <form action="/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="uploaded_file" required>
            <div style="margin-top: 1em;">
                <label for="privado">¿Hacer este archivo privado?</label>
                <input type="checkbox" name="privado" id="privado">
            </div>
            <button type="submit" class="upload-button">Upload</button>
        </form>

        <!-- Botón para volver a la página de inicio -->
        <div style="margin-top: 1em;">
            <a href="/" class="back-button">Volver a Inicio</a>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/subir_archivos.css') }}">
@endsection

