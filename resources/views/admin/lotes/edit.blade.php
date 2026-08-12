@extends('layouts.admin')

@section('title', 'Editar Lote — SystemCOFF 360')

@section('content')
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">Gestión de finca</p>
                <h2 class="text-3xl font-extrabold text-green-950">Editar lote: {{ $lote->nombre }}</h2>
            </div>
            <a href="{{ route('lotes.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">
                <i class="fas fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </header>

    <div class="bg-white rounded-3xl p-8 border border-green-100 shadow-sm max-w-2xl">
        <form action="{{ route('lotes.update', $lote->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Lote</label>
                <input type="text" name="nombre" value="{{ old('nombre', $lote->nombre) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de Plantación</label>
                <input type="text" name="tipo_plantacion" value="{{ old('tipo_plantacion', $lote->tipo_plantacion) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('tipo_plantacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Ubicación / Sector</label>
                <input type="text" name="ubicacion" value="{{ old('ubicacion', $lote->ubicacion) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('ubicacion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Área (Hectáreas)</label>
                    <input type="number" step="0.01" name="area_hectareas" value="{{ old('area_hectareas', $lote->area_hectareas) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('area_hectareas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="activo" {{ old('estado', $lote->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ old('estado', $lote->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('lotes.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition">Cancelar</a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold transition">Actualizar Lote</button>
            </div>
        </form>
    </div>
@endsection
