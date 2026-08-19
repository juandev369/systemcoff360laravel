<!-- resources/views/layouts/worker.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel Trabajador — SystemCOFF 360')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/ico.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glass { background: rgba(255,255,255,.92); backdrop-filter: blur(14px); }
    </style>
</head>
<body class="bg-green-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- SIDEBAR TRABAJADOR -->
        <aside class="w-72 hidden lg:flex flex-col text-white p-6 bg-gradient-to-b from-green-950 to-emerald-950">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-seedling text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-lg">SystemCOFF 360</h1>
                    <p class="text-green-200 text-xs">Panel Trabajador</p>
                </div>
            </div>

            <nav class="space-y-2 flex-1">
                <a href="{{ route('trabajador.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('trabajador.dashboard') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-home w-5"></i> Inicio
                </a>
                <a href="{{ route('trabajador.tareas') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('trabajador.tareas') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-clipboard-list w-5"></i> Mis tareas
                </a>
                <a href="{{ route('trabajador.historial') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('trabajador.historial') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-history w-5"></i> Historial
                </a>
                <a href="{{ route('trabajador.nomina') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('trabajador.nomina') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-credit-card w-5"></i> Mi nómina
                </a>
                <a href="{{ route('trabajador.perfil') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('trabajador.perfil') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-user w-5"></i> Mi perfil
                </a>
            </nav>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-500/20 hover:bg-red-500/30 transition">
                    <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                </button>
            </form>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 p-4 md:p-8">
            @yield('content')
        </main>
    </div>

    @if (session('alert'))
        <script>
            Swal.fire({
                icon: '{{ session('alert.icon') }}',
                title: '{{ session('alert.title') }}',
                text: '{{ session('alert.text') }}',
                confirmButtonColor: '#16a34a'
            });
        </script>
    @endif
</body>
</html>
