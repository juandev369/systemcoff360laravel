@extends('layouts.admin')

@section('title', 'Detalles del Lote — SystemCOFF 360')

@section('content')
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">Gestión de finca</p>
                <h2 class="text-3xl font-extrabold text-green-950">{{ $lote->nombre }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('lotes.edit', $lote->id) }}" class="px-5 py-3 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-bold transition">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <a href="{{ route('lotes.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
        <div class="bg-white rounded-3xl p-8 border border-green-100 shadow-sm space-y-4">
            <h3 class="text-lg font-extrabold text-green-950 border-b border-gray-100 pb-3">Información del Lote</h3>
            
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Tipo de Plantación:</span>
                <span class="font-bold text-green-950">{{ $lote->tipo_plantacion }}</span>
            </div>

            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Ubicación:</span>
                <span class="font-bold text-green-950">{{ $lote->ubicacion ?? 'Sin ubicación' }}</span>
            </div>

            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Área:</span>
                <span class="font-bold text-green-950">{{ number_format($lote->area_hectareas, 2) }} ha</span>
            </div>

            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500 font-medium">Estado:</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $lote->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($lote->estado) }}
                </span>
            </div>

            <div class="flex justify-between py-2">
                <span class="text-gray-500 font-medium">Fecha de Creación:</span>
                <span class="text-gray-700">{{ $lote->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
@endsection
