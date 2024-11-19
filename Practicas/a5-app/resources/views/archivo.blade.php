@extends('layouts.app')

@section('title', $fichero->name)

@section('content')
    <div class="container">
        <a href="/" class="btn btn-secondary" style="margin-top: 20px;">Inicio</a>
        <h1 class="archivo-titulo" style="color: black;">{{ $fichero->name }}</h1>

        <!-- Tarjeta de información -->
        <div class="card mt-4 archivo-card" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; align-items: center; text-align: center;">
            <!-- Información general -->
            <div class="card-body">
                <p>
                    <strong>Size:</strong> 
                    @if (Storage::exists($fichero->path))
                        {{ Storage::size($fichero->path) }} bytes
                    @else
                        Archivo no disponible
                    @endif
                </p>
                <p><strong>Owner:</strong> {{ $fichero->user->name }}</p>
                <p><strong>Created at:</strong> {{ $fichero->created_at->format('d-m-Y H:i') }}</p>
                <p><strong>Updated at:</strong> {{ $fichero->updated_at->format('d-m-Y H:i') }}</p>

                <!-- Contador de descargas -->
                <p><strong>Descargas:</strong> {{ $fichero->descargas }}</p>

                @if (Storage::exists($fichero->path))
                    <a href="/download/{{ $fichero->id }}" class="btn btn-primary archivo-descargar">Descargar</a>
                @else
                    <button class="btn btn-secondary" disabled>No disponible</button>
                @endif
            </div>

            <!-- Gráfico de descargas -->
            <div class="chart-container">
                <h5>Estadísticas de Descargas</h5>
                <canvas id="descargasChart" style="width: 100%; max-width: 300px;"></canvas>
            </div>

            <!-- Generar y mostrar el QR -->
            <div class="qr-code">
                <h5>Descargar mediante QR</h5>
                @if (Storage::exists($fichero->path))
                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(150)->generate(route('download', ['file' => $fichero->id]))) }}" alt="QR para descargar archivo">
                @else
                    <p>QR no disponible</p>
                @endif
            </div>
        </div>

        <!-- Lista de usuarios con los que se ha compartido el archivo -->
        @if($fichero->sharedUsers && $fichero->sharedUsers->count() > 0)
            <div class="mt-4">
                <h5>Archivo compartido con:</h5>
                <ul>
                    @foreach($fichero->sharedUsers as $user)
                        <li>{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Editor de documentos -->
        @if(Str::endsWith($fichero->name, ['.txt', '.md', '.docx', '.pdf']) && Storage::exists($fichero->path))
            <form action="{{ route('archivo.update', ['id' => $fichero->id]) }}" method="POST" style="margin-top: 20px;">
                @csrf
                @method('PUT')
                <div class="word-editor mt-4">
                    <h5>Editor de Documento</h5>
                    @if(Str::endsWith($fichero->name, ['.txt', '.md']))
                        <textarea name="content" rows="20" class="form-control" style="width: 100%; height: 600px;">{{ Storage::get($fichero->path) }}</textarea>
                    @elseif(Str::endsWith($fichero->name, ['.docx']))
                        @php
                            $docContent = '';
                            try {
                                $phpWord = \PhpOffice\PhpWord\IOFactory::load(Storage::path($fichero->path));
                                foreach ($phpWord->getSections() as $section) {
                                    foreach ($section->getElements() as $element) {
                                        if (method_exists($element, 'getText')) {
                                            $docContent .= $element->getText() . "\n";
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                $docContent = 'No se pudo cargar el contenido del archivo.';
                            }
                        @endphp
                        <textarea name="content" rows="20" class="form-control" style="width: 100%; height: 600px;">{{ $docContent }}</textarea>
                    @elseif(Str::endsWith($fichero->name, ['.pdf']))
                        <iframe src="{{ route('ver.pdf', ['id' => $fichero->id]) }}" type="application/pdf" width="100%" height="600px"></iframe>
                    @endif
                </div>
                <button type="submit" class="btn btn-success" style="margin-top: 10px;">Guardar Cambios</button>
            </form>
        @endif

        <!-- Botón para volver -->
        <div style="margin-top: 1em;">
            <a href="/" class="back-button">Volver a Inicio</a>
        </div>
    </div>

    <!-- Script para el gráfico -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('descargasChart').getContext('2d');
        const chartData = {
            labels: {!! json_encode($descargasPorDia->pluck('fecha')) !!}, // Fechas de descargas
            datasets: [{
                label: 'Número de descargas',
                data: {!! json_encode($descargasPorDia->pluck('total')) !!}, // Total de descargas por día
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        };

        const descargasChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/archivo_detalle.css') }}">
@endsection
