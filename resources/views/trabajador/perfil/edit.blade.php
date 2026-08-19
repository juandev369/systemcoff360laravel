@extends('layouts.worker')

@section('title', 'Mi Perfil - Trabajador')

@section('content')
    <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-8 mb-8">
        <p class="text-sm font-bold text-green-600 uppercase tracking-widest">Zona del trabajador</p>
        <h1 class="text-4xl font-extrabold text-green-950 mt-2">Mi perfil</h1>
        <p class="text-gray-600 mt-2">Consulta y actualiza tu información personal.</p>
    </section>

    <!-- Manejo de errores globales de validación -->
    @if ($errors->any())
        <div class="mb-8 bg-red-100 border border-red-300 text-red-800 rounded-2xl p-4">
            <div class="flex items-center gap-2 font-bold mb-2">
                <i class="fas fa-exclamation-circle"></i> Hay errores en el formulario:
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- CARD PERFIL -->
        <section class="bg-white rounded-3xl shadow-sm border border-green-100 p-8">
            <div class="flex flex-col items-center text-center">
                <div class="w-28 h-28 bg-green-600 rounded-full flex items-center justify-center text-white text-5xl mb-4 shadow-lg">
                    <i class="fas fa-user"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-green-950">{{ $usuario->name }}</h2>
                <p class="text-green-600 font-bold mt-1">Trabajador</p>
                <span class="mt-4 px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 uppercase">
                    {{ $usuario->estado }}
                </span>
            </div>

            <div class="mt-8 space-y-4 text-sm">
                <div class="flex items-center gap-3 text-gray-600">
                    <i class="fas fa-id-card text-green-600 w-5"></i>
                    {{ $usuario->dni ?? 'Sin DNI' }}
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <i class="fas fa-phone text-green-600 w-5"></i>
                    {{ $usuario->telefono ?? 'Sin teléfono' }}
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <i class="fas fa-envelope text-green-600 w-5"></i>
                    {{ $usuario->email }}
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    <i class="fas fa-calendar text-green-600 w-5"></i>
                    Registrado: {{ $usuario->created_at ? $usuario->created_at->format('Y-m-d') : '—' }}
                </div>
            </div>
        </section>

        <!-- FORMULARIOS -->
        <section class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-green-100 p-8">
            
            <!-- DATOS BÁSICOS -->
            <h2 class="text-2xl font-bold text-green-950 mb-6">Actualizar información</h2>
            
            @php
                // Dividir el campo "name" en Nombres y Apellidos
                $partesNombre = explode(' ', $usuario->name, 2);
                $nombres = $partesNombre[0] ?? '';
                $apellidos = $partesNombre[1] ?? '';
            @endphp

            <form action="{{ route('trabajador.perfil.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-green-900 mb-2">Nombres</label>
                        <input type="text" name="nombres" value="{{ old('nombres', $nombres) }}" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-green-900 mb-2">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $apellidos) }}" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-green-900 mb-2">DNI</label>
                    <input type="text" name="dni" value="{{ old('dni', $usuario->dni) }}" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-green-900 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-green-900 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <div class="pt-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl transition">
                        <i class="fas fa-save mr-2"></i> Guardar cambios
                    </button>
                </div>
            </form>

            <!-- CAMBIO DE CONTRASEÑA -->
            <div class="mt-8 pt-8 border-t border-green-100">
                <h3 class="text-lg font-bold text-green-950 mb-1">Cambiar contraseña</h3>
                <p class="text-xs text-gray-500 mb-5">Asegúrate de usar una contraseña larga y segura.</p>
                
                <form action="{{ route('trabajador.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-bold text-green-900 mb-2">Contraseña actual</label>
                        <input type="password" name="current_password" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-green-900 mb-2">Nueva contraseña</label>
                        <input type="password" name="password" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-green-900 mb-2">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-green-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition">
                            <i class="fas fa-key mr-2"></i> Actualizar contraseña
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
