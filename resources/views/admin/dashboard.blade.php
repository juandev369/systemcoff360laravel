@extends('layouts.admin')

@section('title', 'Dashboard Administrador — SystemCOFF 360')

@section('content')
    <!-- TOPBAR con botón de alertas -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-green-950">Dashboard</h1>
            <p class="text-sm text-gray-500">Control general de la finca · {{ now()->format('d/m/Y') }}</p>
        </div>

        <div class="relative" id="alertas-wrapper">
            <button id="btn-alertas" class="relative flex items-center gap-2 px-4 py-2.5 rounded-2xl border transition bg-white border-green-100 hover:bg-green-50">
                <i class="fas fa-bell text-gray-400 text-base"></i>
                <span class="text-sm font-bold text-gray-500">Sin alertas</span>
            </button>
        </div>
    </div>

    <!-- ESTADÍSTICAS PRINCIPALES -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <p class="text-sm text-gray-500">Usuarios registrados</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalUsuarios }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            
            <div class="h-2 w-full bg-green-100 rounded-full overflow-hidden">
                @php $porcentajeActivos = $totalUsuarios > 0 ? round(($totalActivos / $totalUsuarios) * 100) : 0; @endphp
                <div class="h-full bg-green-500 rounded-full" style="width: {{ $porcentajeActivos }}%"></div>
            </div>
            <p class="text-xs text-gray-500 mt-3">{{ $totalActivos }} activos · {{ $totalInactivos }} inactivos</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <p class="text-sm text-gray-500">Lotes registrados</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalLotes }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fas fa-map text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Control de zonas productivas de la finca</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <p class="text-sm text-gray-500">Tareas registradas</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalTareas }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-lime-100 text-lime-700 flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Seguimiento de actividades del personal</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 transition-transform duration-200 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <p class="text-sm text-gray-500">Cosechas registradas</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalCosechas }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                    <i class="fas fa-mug-hot text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Historial productivo del café</p>
        </div>
    </section>

    <!-- BLOQUES SECUNDARIOS -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Operación de finca -->
        <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-extrabold text-green-950">Resumen operativo</h3>
                    <p class="text-sm text-gray-500">Vista rápida del estado general del sistema</p>
                </div>
                <i class="fas fa-leaf text-green-500 text-2xl"></i>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl bg-green-50 p-4">
                    <p class="text-xs text-gray-500">Insumos</p>
                    <p class="text-2xl font-extrabold text-green-900">{{ $totalInsumos }}</p>
                </div>
                <div class="rounded-2xl bg-green-50 p-4">
                    <p class="text-xs text-gray-500">Herramientas</p>
                    <p class="text-2xl font-extrabold text-green-900">{{ $totalHerramientas }}</p>
                </div>
                <div class="rounded-2xl bg-green-50 p-4">
                    <p class="text-xs text-gray-500">Activos</p>
                    <p class="text-2xl font-extrabold text-green-900">{{ $totalActivosFinca }}</p>
                </div>
                <div class="rounded-2xl bg-green-50 p-4">
                    <p class="text-xs text-gray-500">Proveedores</p>
                    <p class="text-2xl font-extrabold text-green-900">{{ $totalProveedores }}</p>
                </div>
                <div class="rounded-2xl bg-yellow-50 p-4">
                    <p class="text-xs text-gray-500">Compras</p>
                    <p class="text-2xl font-extrabold text-yellow-700">{{ $totalCompras }}</p>
                </div>
                <div class="rounded-2xl bg-yellow-50 p-4">
                    <p class="text-xs text-gray-500">Ventas</p>
                    <p class="text-2xl font-extrabold text-yellow-700">{{ $totalVentas }}</p>
                </div>
                <div class="rounded-2xl bg-blue-50 p-4">
                    <p class="text-xs text-gray-500">Mantenimientos</p>
                    <p class="text-2xl font-extrabold text-blue-700">{{ $totalMantenimientos }}</p>
                </div>
                <div class="rounded-2xl bg-blue-50 p-4">
                    <p class="text-xs text-gray-500">Trabajadores</p>
                    <p class="text-2xl font-extrabold text-blue-700">{{ $totalTrabajadores }}</p>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h3 class="text-xl font-extrabold text-green-950 mb-2">Acciones rápidas</h3>
            <p class="text-sm text-gray-500 mb-5">Atajos administrativos</p>

            <div class="space-y-3">
                <a href="{{ route('usuarios.create') }}" class="flex items-center gap-3 p-4 rounded-2xl bg-green-50 hover:bg-green-100 transition">
                    <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <p class="font-bold text-green-950">Crear usuario</p>
                        <p class="text-xs text-gray-500">Administrador o trabajador</p>
                    </div>
                </a>

                <a href="#" class="flex items-center gap-3 p-4 rounded-2xl bg-yellow-50 hover:bg-yellow-100 transition">
                    <div class="w-10 h-10 rounded-xl bg-yellow-500 text-white flex items-center justify-center">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <p class="font-bold text-green-950">Revisar inventario</p>
                        <p class="text-xs text-gray-500">Insumos y herramientas</p>
                    </div>
                </a>

                <a href="#" class="flex items-center gap-3 p-4 rounded-2xl bg-blue-50 hover:bg-blue-100 transition">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        <p class="font-bold text-green-950">Asignar tareas</p>
                        <p class="text-xs text-gray-500">Actividades de campo</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- TABLAS -->
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Usuarios recientes -->
        <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-xl font-extrabold text-green-950">Usuarios recientes</h3>
                    <p class="text-sm text-gray-500">Últimos usuarios creados</p>
                </div>
                <i class="fas fa-user-clock text-green-500 text-xl"></i>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="py-3">Nombre</th>
                            <th class="py-3">Rol</th>
                            <th class="py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuariosRecientes as $u)
                            <tr class="border-b last:border-0">
                                <td class="py-3">
                                    <p class="font-bold text-green-950">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $u->email }}</p>
                                </td>
                                <td class="py-3">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                        {{ $u->role->nombre ?? 'Trabajador' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="px-3 py-1 rounded-full {{ $u->estado === 'activo' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} text-xs font-bold">
                                        {{ ucfirst($u->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-gray-500">No hay usuarios recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actividad reciente -->
        <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-xl font-extrabold text-green-950">Actividad del sistema</h3>
                    <p class="text-sm text-gray-500">Resumen de registros recientes</p>
                </div>
                <i class="fas fa-bell text-green-500 text-xl"></i>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-2xl bg-green-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <p class="font-bold text-green-950">Tareas recientes</p>
                            <p class="text-xs text-gray-500">Registros encontrados</p>
                        </div>
                    </div>
                    <span class="text-2xl font-extrabold text-green-900">{{ count($tareasRecientes) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 rounded-2xl bg-yellow-50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-yellow-500 text-white flex items-center justify-center">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <div>
                            <p class="font-bold text-green-950">Cosechas recientes</p>
                            <p class="text-xs text-gray-500">Producción registrada</p>
                        </div>
                    </div>
                    <span class="text-2xl font-extrabold text-yellow-700">{{ count($cosechasRecientes) }}</span>
                </div>
            </div>
        </div>
    </section>
@endsection