@extends('layouts.worker')

@section('title', 'Mis tareas — SystemCOFF 360')

@section('content')
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-8 mb-8">
        <p class="text-sm font-bold text-green-600 uppercase tracking-widest">Zona del trabajador</p>
        <h1 class="text-4xl font-extrabold text-green-950 mt-2">Mis tareas</h1>
        <p class="text-gray-600 mt-2">Revisa tus tareas asignadas, sube evidencias y agrega comentarios.</p>
    </section>

    <!-- RESUMEN -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-green-100 p-6 shadow-sm">
            <p class="text-gray-500">Total</p>
            <h2 class="text-4xl font-extrabold text-green-900 mt-2">{{ $total }}</h2>
        </div>
        <div class="bg-white rounded-2xl border border-yellow-100 p-6 shadow-sm">
            <p class="text-gray-500">Pendientes</p>
            <h2 class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $pendientes }}</h2>
        </div>
        <div class="bg-white rounded-2xl border border-blue-100 p-6 shadow-sm">
            <p class="text-gray-500">En proceso</p>
            <h2 class="text-4xl font-extrabold text-blue-600 mt-2">{{ $proceso }}</h2>
        </div>
        <div class="bg-white rounded-2xl border border-green-100 p-6 shadow-sm">
            <p class="text-gray-500">Finalizadas</p>
            <h2 class="text-4xl font-extrabold text-green-600 mt-2">{{ $finalizadas }}</h2>
        </div>
    </section>

    <!-- LISTADO DE TAREAS -->
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-6">
        <h2 class="text-2xl font-bold text-green-950 mb-6">Listado de tareas</h2>

        @if($tareas->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-5">
                <i class="fas fa-info-circle mr-2"></i> No tienes tareas asignadas en este momento.
            </div>
        @else
            <div class="space-y-6">
                @foreach ($tareas as $tarea)
                    @php
                        $colorBadge = match ($tarea->estado_asignacion) {
                            'pendiente' => 'bg-yellow-100 text-yellow-700',
                            'en proceso' => 'bg-blue-100 text-blue-700',
                            default => 'bg-green-100 text-green-700'
                        };
                    @endphp

                    <div class="border border-green-100 rounded-3xl p-6 bg-white shadow-sm">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            
                            <!-- INFO TAREA -->
                            <div>
                                <h3 class="text-2xl font-bold text-green-950">{{ $tarea->descripcion }}</h3>
                                <div class="flex gap-3 mt-4 flex-wrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                        Prioridad: {{ $tarea->prioridad }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $colorBadge }}">
                                        {{ $tarea->estado_asignacion }}
                                    </span>
                                </div>
                                <div class="mt-5 text-sm text-gray-600 space-y-2">
                                    <p><i class="fas fa-calendar-plus text-green-600 mr-2"></i> <strong>Fecha asignación:</strong> {{ $tarea->fecha_asignacion }}</p>
                                    <p><i class="fas fa-calendar-check text-green-600 mr-2"></i> <strong>Fecha límite:</strong> {{ $tarea->fecha_limite ?? 'Sin fecha límite' }}</p>
                                </div>
                            </div>

                            <!-- ACCIONES (EVIDENCIA) -->
                            <div>
                                @if ($tarea->estado_asignacion === 'pendiente')
                                    <form action="{{ route('trabajador.tareas.start', $tarea->id_tarea) }}" method="POST" class="mb-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-2xl flex items-center justify-center gap-2 transition">
                                            <i class="fas fa-play-circle"></i> Iniciar tarea
                                        </button>
                                    </form>
                                @endif

                                @if ($tarea->estado_asignacion !== 'finalizada')
                                    <form action="{{ route('trabajador.tareas.complete', $tarea->id_tarea) }}" method="POST" enctype="multipart/form-data" class="bg-green-50 border border-green-200 rounded-3xl p-5">
                                        @csrf
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-12 h-12 rounded-2xl bg-green-600 text-white flex items-center justify-center">
                                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-green-950">Subir evidencia</h4>
                                                <p class="text-xs text-gray-500">Adjunta foto, PDF o documento.</p>
                                            </div>
                                        </div>

                                        <label class="block border-2 border-dashed border-green-300 rounded-2xl bg-white p-6 text-center cursor-pointer hover:bg-green-100 transition">
                                            <i class="fas fa-file-upload text-4xl text-green-600 mb-3"></i>
                                            <p class="text-sm font-bold text-green-900">Selecciona tu archivo</p>
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, PDF o DOCX</p>
                                            <input type="file" name="evidencia" required accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden">
                                        </label>

                                        @error('evidencia')
                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                        @enderror

                                        <div class="mt-4">
                                            <label class="block text-sm font-bold text-green-900 mb-2">Comentario</label>
                                            <textarea name="comentario" rows="4" required placeholder="Escribe una observación sobre la tarea realizada..." class="w-full border border-green-200 rounded-2xl px-4 py-3 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                                        </div>

                                        <button type="submit" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-2xl shadow-sm transition">
                                            <i class="fas fa-check-circle mr-2"></i> Enviar evidencia y completar
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-green-100 border border-green-300 text-green-800 rounded-3xl p-6 text-center">
                                        <i class="fas fa-check-circle text-4xl mb-3"></i>
                                        <h4 class="font-bold text-lg">Tarea finalizada</h4>
                                        <p class="text-sm mt-1">Esta tarea ya fue marcada como completada.</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
