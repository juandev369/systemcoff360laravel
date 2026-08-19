@extends('layouts.admin')

@section('title', 'Gestión de tareas — SystemCOFF 360')

@section('content')
    <header class="bg-white rounded-3xl p-6 border border-green-100 mb-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-1">
                    Administrador
                </p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-green-950">
                    Gestión de tareas
                </h2>
                <p class="text-gray-500 mt-1 text-sm">
                    Crea tareas, asígnalas a trabajadores, revisa evidencias y controla el estado.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                <i class="fas fa-clipboard-check text-2xl"></i>
            </div>
        </div>
    </header>

    <!-- FORMULARIO CREAR TAREA -->
    <section class="bg-white rounded-3xl p-6 border border-green-100 mb-8 shadow-sm">
        <h3 class="text-2xl font-extrabold text-green-950 mb-5">
            Asignar nueva tarea
        </h3>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-xl text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tareas.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-green-950 mb-2">Descripción de la tarea</label>
                <textarea name="descripcion" rows="3" required class="w-full rounded-xl border border-green-200 p-3 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Ejemplo: Realizar limpieza en el lote La María">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-green-950 mb-2">Trabajador</label>
                <select name="id_usuario" required class="w-full rounded-xl border border-green-200 p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Seleccione un trabajador</option>
                    @foreach ($trabajadores as $trabajador)
                        <option value="{{ $trabajador->id }}" {{ old('id_usuario') == $trabajador->id ? 'selected' : '' }}>
                            {{ $trabajador->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-green-950 mb-2">Prioridad</label>
                <select name="prioridad" required class="w-full rounded-xl border border-green-200 p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="baja" {{ old('prioridad') == 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ old('prioridad') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ old('prioridad') == 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-green-950 mb-2">Fecha límite</label>
                <input type="date" name="fecha_limite" required value="{{ old('fecha_limite') }}" class="w-full rounded-xl border border-green-200 p-3 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold transition">
                    <i class="fas fa-plus-circle mr-2"></i> Crear y asignar tarea
                </button>
            </div>
        </form>
    </section>

    <!-- LISTADO DE TAREAS -->
    <section class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
        <h3 class="text-2xl font-extrabold text-green-950 mb-5">
            Tareas asignadas
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-green-100 text-green-950">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Tarea</th>
                        <th class="p-3 text-left">Trabajador</th>
                        <th class="p-3 text-left">Prioridad</th>
                        <th class="p-3 text-left">Estado</th>
                        <th class="p-3 text-left">Fecha límite</th>
                        <th class="p-3 text-left">Evidencia</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tareas as $tarea)
                        @php
                            $estado = strtolower($tarea->estado);
                            $colorEstado = match ($estado) {
                                'completada', 'finalizada', 'en_progreso' => 'bg-emerald-100 text-emerald-700',
                                'en proceso', 'en_progreso' => 'bg-blue-100 text-blue-700',
                                default => 'bg-yellow-100 text-yellow-700'
                            };
                        @endphp

                        <tr class="border-b border-green-100 hover:bg-green-50/50 transition">
                            <td class="p-3 font-bold">{{ $tarea->id_tarea }}</td>
                            <td class="p-3">{{ $tarea->descripcion }}</td>
                            <td class="p-3">{{ $tarea->nombre ?? 'Sin asignar' }}</td>
                            <td class="p-3 uppercase text-xs font-bold">{{ $tarea->prioridad }}</td>
                            <td class="p-3">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $colorEstado }}">
                                    {{ ucfirst($tarea->estado) }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-600">{{ $tarea->fecha_limite }}</td>
                            <td class="p-3">
                                @if (!empty($tarea->archivo))
                                    <a href="{{ asset($tarea->archivo) }}" target="_blank" class="text-green-700 font-bold hover:underline flex items-center gap-1">
                                        <i class="fas fa-file-download"></i> Ver evidencia
                                    </a>
                                    <p class="text-gray-500 text-xs mt-1">{{ $tarea->descripcion_evidencia }}</p>
                                @else
                                    <span class="text-gray-400 text-xs">Sin evidencia</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex flex-col gap-2">
                                    <!-- CAMBIAR ESTADO -->
                                    <form action="{{ route('tareas.status', $tarea->id_tarea) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estado" class="rounded-lg border border-green-200 p-2 text-xs focus:ring-2 focus:ring-green-500 outline-none">
                                            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="en proceso" {{ $estado === 'en proceso' ? 'selected' : '' }}>En proceso</option>
                                            <option value="completada" {{ $estado === 'completada' ? 'selected' : '' }}>Completada</option>
                                            <option value="finalizada" {{ $estado === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                        </select>
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition text-xs font-bold">
                                            Guardar
                                        </button>
                                    </form>

                                    <!-- ELIMINAR -->
                                    <form action="{{ route('tareas.destroy', $tarea->id_tarea) }}" method="POST" class="form-eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition text-xs font-bold w-full text-center">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500">
                                No hay tareas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¿Eliminar tarea?',
                    text: 'Se eliminarán también las evidencias y asignaciones vinculadas.',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
