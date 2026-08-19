@extends('layouts.worker')

@section('content')
    <!-- HEADER -->
    <header class="glass rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">
                    Zona del trabajador
                </p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-green-950">
                    Bienvenido, {{ auth()->user()->name }}
                </h2>
                <p class="text-gray-500 mt-2">
                    Aquí puedes consultar tus tareas asignadas, revisar tu historial y actualizar tu información.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-700">{{ now()->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">Panel activo</p>
                </div>

                <!-- Botón notificaciones trabajador -->
                <div class="relative" id="notif-wrapper">
                    <button id="btn-notif" onclick="toggleNotif()" class="relative w-12 h-12 rounded-2xl border flex items-center justify-center transition {{ $totalNotif > 0 ? 'bg-yellow-50 border-yellow-200 hover:bg-yellow-100' : 'bg-white border-green-100 hover:bg-green-50' }}">
                        <i class="fas fa-bell {{ $totalNotif > 0 ? 'text-yellow-500' : 'text-gray-400' }} text-lg"></i>
                        @if ($totalNotif > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white text-[10px] font-black rounded-full flex items-center justify-center shadow">
                                {{ $totalNotif }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown notificaciones -->
                    @if ($totalNotif > 0)
                        <div id="notif-dropdown" class="hidden absolute right-0 top-14 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bell text-yellow-500"></i>
                                    <span class="font-bold text-gray-800 text-sm">Notificaciones</span>
                                </div>
                                <span class="bg-red-100 text-red-600 text-xs font-black px-2 py-0.5 rounded-full">
                                    {{ $totalNotif }}
                                </span>
                            </div>

                            <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                                @foreach ($notifTrabajador as $n)
                                    <a href="{{ $n['link'] }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition group">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5 {{ $n['tipo'] === 'tarea' ? 'bg-green-100' : 'bg-blue-100' }}">
                                            <i class="fas {{ $n['ico'] }} text-xs {{ $n['tipo'] === 'tarea' ? 'text-green-600' : 'text-blue-600' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold {{ $n['tipo'] === 'tarea' ? 'text-green-700' : 'text-blue-700' }}">
                                                {{ $n['titulo'] }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $n['msg'] }}</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-300 text-xs mt-1 group-hover:text-gray-500 transition"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="w-12 h-12 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-lg"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- TARJETAS DE ESTADÍSTICAS -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-green-100 hover:bg-green-50 transition cursor-pointer">
            <p class="text-sm text-gray-500">Mis tareas</p>
            <h3 class="text-4xl font-extrabold text-green-950">{{ $totalTareas }}</h3>
            <i class="fas fa-clipboard-list text-green-600 text-2xl mt-4"></i>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-yellow-100">
            <p class="text-sm text-gray-500">Pendientes</p>
            <h3 class="text-4xl font-extrabold text-yellow-700">{{ $tareasPendientes }}</h3>
            <i class="fas fa-clock text-yellow-600 text-2xl mt-4"></i>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-blue-100">
            <p class="text-sm text-gray-500">En proceso</p>
            <h3 class="text-4xl font-extrabold text-blue-700">{{ $tareasProceso }}</h3>
            <i class="fas fa-spinner text-blue-600 text-2xl mt-4"></i>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-emerald-100">
            <p class="text-sm text-gray-500">Finalizadas</p>
            <h3 class="text-4xl font-extrabold text-emerald-700">{{ $tareasFinalizadas }}</h3>
            <i class="fas fa-check-circle text-emerald-600 text-2xl mt-4"></i>
        </div>
    </section>

    <!-- ACCIONES RÁPIDAS -->
    <section class="bg-white rounded-3xl p-6 border border-green-100">
        <h3 class="text-xl font-extrabold text-green-950 mb-4">Acciones rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" class="p-5 rounded-2xl bg-green-50 hover:bg-green-100 transition">
                <i class="fas fa-tasks text-green-700 text-2xl mb-3"></i>
                <p class="font-bold text-green-950">Ver mis tareas</p>
                <p class="text-sm text-gray-500">Consulta tus actividades asignadas.</p>
            </a>
            <a href="#" class="p-5 rounded-2xl bg-yellow-50 hover:bg-yellow-100 transition">
                <i class="fas fa-credit-card text-yellow-600 text-2xl mb-3"></i>
                <p class="font-bold text-green-950">Mi nómina</p>
                <p class="text-sm text-gray-500">Consulta tus pagos recibidos.</p>
            </a>
            <a href="#" class="p-5 rounded-2xl bg-blue-50 hover:bg-blue-100 transition">
                <i class="fas fa-user-edit text-blue-600 text-2xl mb-3"></i>
                <p class="font-bold text-green-950">Mi perfil</p>
                <p class="text-sm text-gray-500">Actualiza tus datos personales.</p>
            </a>
        </div>
    </section>

    <!-- Script para desplegar notificaciones -->
    <script>
        function toggleNotif() {
            const dd = document.getElementById('notif-dropdown');
            if (dd) dd.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                const dd = document.getElementById('notif-dropdown');
                if (dd) dd.classList.add('hidden');
            }
        });
    </script>
@endsection
