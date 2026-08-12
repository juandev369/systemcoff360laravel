<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SystemCOFF 360 — Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #064e3b 0%, #16a34a 60%, #22c55e 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="gradient-bg text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-seedling"></i>
                </div>
                <span class="font-black text-lg">SystemCOFF 360</span>
            </div>
            <div class="flex items-center gap-6">
                <span class="text-sm font-semibold">
                    Hola, {{ auth()->user()->name }}
                    @if(auth()->user()->role) · <span class="text-green-200">{{ auth()->user()->role->nombre }}</span>@endif
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-xl text-sm font-bold transition">
                        <i class="fas fa-sign-out-alt mr-1"></i> Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-16 text-center">
        <div class="w-20 h-20 gradient-bg rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-xl">
            <i class="fas fa-check text-white text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-green-950 mb-3">¡Bienvenido a tu panel!</h1>
        <p class="text-gray-500 max-w-md mx-auto">
            Login y registro funcionando correctamente. Los próximos módulos (lotes, cosechas, inventario, etc.) se irán agregando aquí.
        </p>
    </div>
</body>
</html>
