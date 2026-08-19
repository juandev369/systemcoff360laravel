@extends('layouts.worker')

@section('title', 'Historial - Trabajador')

@section('content')
    <!-- HEADER -->
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-8 mb-8">
        <p class="text-sm font-bold text-green-600 uppercase tracking-widest">Zona del trabajador</p>
        <h1 class="text-4xl font-extrabold text-green-950 mt-2">Historial de tareas</h1>
        <p class="text-gray-600 mt-2">Consulta todas las tareas asignadas y finalizadas.</p>
    </section>

    <!-- CARD TOTAL -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-green-100 p-6 shadow-sm">
            <p class="text-gray-500">Total tareas</p>
            <h2 class="text-4xl font-extrabold text-green-900 mt-2">{{ $totalHistorial }}</h2>
            <i class="fas fa-history text-green-600 text-2xl mt-4"></i>
        </div>
    </section>

    <!-- FILTRO POR PERÍODO -->
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-6 mb-6">
        <!-- El form hace un GET a la misma ruta actual para aplicar los filtros -->
        <form action="{{ route('trabajador.historial') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-bold text-green-900 mb-1">Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="border border-green-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <div>
                <label class="block text-sm font-bold text-green-900 mb-1">Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="border border-green-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-5 py-2 rounded-xl text-sm transition">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            
            @if (request()->filled('desde') || request()->filled('hasta'))
                <a href="{{ route('trabajador.historial') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-5 py-2 rounded-xl text-sm transition">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </a>
            @endif
        </form>
    </section>

    <!-- TABLA DE HISTORIAL -->
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-6">
        <h2 class="text-2xl font-bold text-green-950 mb-6">Historial</h2>

        @if ($historial->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-5">
                <i class="fas fa-info-circle mr-2"></i> No hay tareas registradas.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-green-100 text-green-900">
                            <th class="p-4 text-left rounded-l-xl">Descripción</th>
                            <th class="p-4 text-left">Prioridad</th>
                            <th class="p-4 text-left">Estado</th>
                            <th class="p-4 text-left">Fecha asignación</th>
                            <th class="p-4 text-left">Fecha límite</th>
                            <th class="p-4 text-left rounded-r-xl">Evidencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($historial as $item)
                            <tr class="border-b border-green-100 hover:bg-green-50 transition">
                                <td class="p-4 text-gray-700">{{ $item->descripcion }}</td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">
                                        {{ $item->prioridad }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 uppercase">
                                        {{ $item->estado_asignacion }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-600">{{ $item->fecha_asignacion }}</td>
                                <td class="p-4 text-gray-600">{{ $item->fecha_limite ?? 'Sin fecha' }}</td>
                                <td class="p-4">
                                    @if (!empty($item->archivo))
                                        <!-- Utilizamos asset() para enlazar correctamente al archivo físico subido -->
                                        <a href="{{ asset($item->archivo) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-green-700 transition">
                                            <i class="fas fa-eye"></i> Ver evidencia
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm">Sin evidencia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
