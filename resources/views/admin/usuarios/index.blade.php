@extends('layouts.admin')

@section('title', 'Usuarios — SystemCOFF 360')

@section('content')
    <!-- HEADER -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">
                    Gestión administrativa
                </p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">
                    Usuarios del sistema
                </h1>
                <p class="text-gray-500 mt-2">
                    Consulta, filtra, activa, desactiva o elimina usuarios registrados en SystemCOFF 360.
                </p>
            </div>

            <a href="{{ route('usuarios.create') }}" class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-2xl font-bold transition">
                <i class="fas fa-user-plus"></i>
                Crear trabajador
            </a>
        </div>
    </header>

    <!-- TARJETAS -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm text-gray-500">Total usuarios</p>
            <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totalUsuarios }}</h3>
            <p class="text-xs text-gray-500 mt-2">Registrados en el sistema</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm text-gray-500">Administradores</p>
            <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totalAdmins }}</h3>
            <p class="text-xs text-gray-500 mt-2">Usuarios con acceso total</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm text-gray-500">Trabajadores</p>
            <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totalTrabajadores }}</h3>
            <p class="text-xs text-gray-500 mt-2">Personal operativo</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform hover:-translate-y-1 hover:shadow-lg">
            <p class="text-sm text-gray-500">Usuarios activos</p>
            <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totalActivos }}</h3>
            <p class="text-xs text-gray-500 mt-2">{{ $totalInactivos }} usuarios inactivos</p>
        </div>
    </section>

    <!-- FILTROS -->
    <section class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm mb-8">
        <form method="GET" action="{{ route('usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-green-900 mb-2">Buscar usuario</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre, DNI o correo..."
                       class="w-full px-4 py-3 rounded-2xl border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-green-900 mb-2">Rol</label>
                <select name="rol" class="w-full px-4 py-3 rounded-2xl border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos</option>
                    <option value="administrador" {{ request('rol') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="trabajador" {{ request('rol') === 'trabajador' ? 'selected' : '' }}>Trabajador</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-green-900 mb-2">Estado</label>
                <select name="estado" class="w-full px-4 py-3 rounded-2xl border border-green-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="md:col-span-4 flex flex-col md:flex-row gap-3">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-2xl font-bold transition">
                    <i class="fas fa-search mr-2"></i> Filtrar
                </button>

                <a href="{{ route('usuarios.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-2xl font-bold text-center transition">
                    Limpiar filtros
                </a>
            </div>
        </form>
    </section>

    <!-- TABLA -->
    <section class="bg-white rounded-3xl border border-green-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-green-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-extrabold text-green-950">
                    Lista completa de usuarios
                </h2>
                <p class="text-sm text-gray-500">
                    Mostrando {{ $usuarios->count() }} usuario(s).
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-green-50 text-green-900">
                    <tr>
                        <th class="px-5 py-4 text-left">Usuario</th>
                        <th class="px-5 py-4 text-left">Contacto</th>
                        <th class="px-5 py-4 text-left">Rol</th>
                        <th class="px-5 py-4 text-left">Estado</th>
                        <th class="px-5 py-4 text-left">Registro</th>
                        <th class="px-5 py-4 text-right">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($usuarios as $u)
                    @php
                        $esYo = auth()->id() === $u->id;
                        $nuevoEstado = $u->estado === 'activo' ? 'inactivo' : 'activo';
                        // Simulamos la obtención del nombre del rol si usas una relación
                        $nombreRol = $u->role_id === 1 ? 'Administrador' : 'Trabajador';
                    @endphp
                    <tr class="border-b last:border-0 hover:bg-green-50/50">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-extrabold text-green-950">
                                        {{ $u->name }}
                                        @if ($esYo)
                                            <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Tú</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        DNI: {{ $u->dni ?? 'Sin DNI' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-700">{{ $u->email }}</p>
                            <p class="text-xs text-gray-500">{{ $u->telefono ?? 'Sin teléfono' }}</p>
                        </td>

                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $u->role_id === 1 ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                {{ $nombreRol }}
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $u->estado === 'activo' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($u->estado) }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-gray-600">
                            {{ $u->created_at->format('Y-m-d') }}
                        </td>

                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-2">
                                @if (!$esYo)
                                    <!-- ACTIVAR / DESACTIVAR -->
                                    <form action="{{ route('usuarios.status', $u->id) }}" method="POST" class="form-cambiar-estado">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="estado" value="{{ $nuevoEstado }}">
                                        
                                        <button type="submit"
                                                class="px-3 py-2 rounded-xl {{ $u->estado === 'activo' ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}"
                                                title="{{ $u->estado === 'activo' ? 'Desactivar usuario' : 'Activar usuario' }}">
                                            <i class="fas {{ $u->estado === 'activo' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                        </button>
                                    </form>

                                    <!-- ELIMINAR -->
                                    <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" class="form-eliminar">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Sin acciones</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-500">
                            No se encontraron usuarios con esos filtros.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Script para alertas (se mantienen igual que en tu archivo original) -->
    <script>
    document.querySelectorAll('.form-cambiar-estado').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const estado = this.querySelector('input[name="estado"]').value;
            const texto = estado === 'activo'
                ? '¿Deseas volver a activar este usuario?'
                : '¿Deseas desactivar este usuario?';

            Swal.fire({
                icon: 'question',
                title: 'Confirmar acción',
                text: texto,
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: '¿Eliminar usuario?',
                text: 'Esta acción no se puede deshacer.',
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
