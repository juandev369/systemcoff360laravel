@extends('layouts.admin')

@section('title', 'Lotes — SystemCOFF 360')

@section('content')
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">Gestión de finca</p>
                <h2 class="text-3xl font-extrabold text-green-950">Lotes de cultivo</h2>
                <p class="text-gray-500 mt-2">Administra ubicación, plantación, área y actividades de mantenimiento.</p>
            </div>

            <!-- Botón para ir al formulario de crear -->
            <a href="{{ route('lotes.create') }}" class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-2xl font-bold transition-colors">
                <i class="fas fa-plus"></i> Crear lote
            </a>
        </div>
    </header>

    <section class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-green-100">
            <p class="text-gray-500 text-sm">Total lotes</p>
            <h3 class="text-4xl font-extrabold text-green-950">{{ $totalLotes }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-green-100">
            <p class="text-gray-500 text-sm">Lotes activos</p>
            <h3 class="text-4xl font-extrabold text-green-950">{{ $totalActivos }}</h3>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-green-100">
            <p class="text-gray-500 text-sm">Área total</p>
            <h3 class="text-4xl font-extrabold text-green-950">{{ number_format($totalArea, 2) }} ha</h3>
        </div>
    </section>

    <section class="bg-white rounded-3xl border border-green-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-green-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-extrabold text-green-950">Listado de lotes</h3>
                <p class="text-sm text-gray-500">Crea, edita, consulta detalles y cambia el estado de cada lote.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-green-50 text-green-900">
                    <tr>
                        <th class="px-5 py-4 text-left">Lote</th>
                        <th class="px-5 py-4 text-left">Ubicación</th>
                        <th class="px-5 py-4 text-left">Plantación</th>
                        <th class="px-5 py-4 text-left">Área</th>
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lotes as $lote)
                        <tr class="border-b last:border-0 hover:bg-green-50/50">
                            <td class="px-5 py-4">
                                <p class="font-bold text-green-950">{{ $lote->nombre }}</p>
                                <p class="text-xs text-gray-500">Registrado: {{ $lote->created_at->format('Y-m-d') }}</p>
                            </td>
                            <td class="px-5 py-4">{{ $lote->ubicacion ?? 'Sin ubicación' }}</td>
                            <td class="px-5 py-4">{{ $lote->tipo_plantacion }}</td>
                            <td class="px-5 py-4">{{ number_format($lote->area_hectareas, 2) }} ha</td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $lote->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($lote->estado) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('lotes.show', $lote->id) }}" class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('lotes.edit', $lote->id) }}" class="px-3 py-2 rounded-xl bg-yellow-50 text-yellow-700 hover:bg-yellow-100" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-gray-500">
                                No hay lotes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection