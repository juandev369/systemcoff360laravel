<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SystemCOFF 360')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/ico.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Asegúrate de tener Tailwind y FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-green-50 min-h-screen">

    <div class="min-h-screen flex">
        <!-- SIDEBAR ADMINISTRADOR -->
        <aside class="hidden lg:flex w-72 bg-green-950 text-white p-6 flex-col">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-seedling text-xl"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-lg">SystemCOFF 360</h1>
                    <p class="text-green-200 text-xs">Administrador</p>
                </div>
            </div>

            <nav class="space-y-2 flex-1">
                <!-- Enlace al Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-chart-line w-5"></i> Dashboard
                </a>
                
                <!-- Enlace a la pantalla CREAR USUARIO -->
                <a href="{{ route('usuarios.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('usuarios.create') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-user-plus w-5"></i> Crear usuario
                </a>

                <!-- Enlace a la pantalla LISTA DE USUARIOS -->
                <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('usuarios.index') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-users w-5"></i> Usuarios
                </a>
                
                <!-- Enlace a LOTES -->
                <a href="{{ route('lotes.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('lotes.*') ? 'bg-green-500/20 text-green-100' : 'hover:bg-white/10 transition' }}">
                    <i class="fas fa-map-marked-alt w-5"></i> Lotes
                </a>
            </nav>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full px-4 py-3 rounded-xl bg-red-500/20 hover:bg-red-500/30 text-left transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
                </button>
            </form>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 p-5 md:p-8">
            @yield('content')
        </main>
    </div>

    <!-- MANEJO GLOBAL DE ALERTAS CON SWEETALERT2 -->
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