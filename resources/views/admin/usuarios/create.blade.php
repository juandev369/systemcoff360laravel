@extends('layouts.admin')

@section('title', 'Crear Trabajador — SystemCOFF 360')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- HEADER -->
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-green-100 mb-8">
            <a href="{{ route('admin.dashboard') }}" class="text-green-700 font-bold text-sm hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Volver al dashboard
            </a>

            <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">
                        Gestión de usuarios
                    </p>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">
                        Registrar trabajador
                    </h1>
                    <p class="text-gray-500 mt-2">
                        Crea una cuenta para un trabajador sin salir del panel administrativo.
                    </p>
                </div>

                <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- FORMULARIO -->
        <div class="bg-white rounded-3xl p-7 md:p-9 shadow-sm border border-green-100">
            
            <!-- Mostrar errores de validación del backend si existen -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
                    <ul class="list-disc list-inside text-sm font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('usuarios.store') }}" method="POST" id="formCrearUsuario">
                @csrf
                <input type="hidden" name="rol" value="trabajador">

                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-xl flex items-center justify-center font-bold">1</div>
                        <h2 class="text-lg font-extrabold text-green-950">Datos personales</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombres" value="{{ old('nombres') }}" required maxlength="100" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Ej. Juan Carlos">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" required maxlength="100" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Ej. Pérez Rodríguez">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                DNI / Cédula <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="dni" value="{{ old('dni') }}" required maxlength="20" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Ej. 1060123456">
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-xl flex items-center justify-center font-bold">2</div>
                        <h2 class="text-lg font-extrabold text-green-950">Información de contacto</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Correo electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required maxlength="150" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="trabajador@correo.com">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Teléfono
                            </label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" maxlength="30" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Ej. 3001234567">
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-xl flex items-center justify-center font-bold">3</div>
                        <h2 class="text-lg font-extrabold text-green-950">Acceso al sistema</h2>
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password" required minlength="8" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Mínimo 8 caracteres">
                            <p class="text-xs text-gray-500 mt-2">Debe tener mínimo 8 caracteres.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-green-900 mb-2">
                                Confirmar contraseña <span class="text-red-500">*</span>
                            </label>
                            <!-- El name es password_confirmation para que Laravel lo detecte automáticamente -->
                            <input type="password" name="password_confirmation" id="confirmar_password" required minlength="8" class="w-full border border-green-200 rounded-2xl px-4 py-3 outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition" placeholder="Repite la contraseña">
                            <p id="mensajePassword" class="text-xs font-bold mt-2"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-100 rounded-2xl p-5 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600 text-white flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="font-bold text-green-950">Rol asignado</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Este formulario registra únicamente usuarios con rol <strong>trabajador</strong>.
                                El trabajador podrá acceder al sistema con el correo y contraseña asignados.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-3">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-5 py-4 rounded-2xl font-bold shadow-lg shadow-green-900/20 transition">
                        <i class="fas fa-save mr-2"></i>
                        Registrar trabajador
                    </button>

                    <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-4 rounded-2xl font-bold transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script de validación en tiempo real para las contraseñas -->
    <script>
        const password = document.getElementById('password');
        const confirmar = document.getElementById('confirmar_password');
        const mensaje = document.getElementById('mensajePassword');

        function validarPasswords() {
            if (!confirmar.value) {
                mensaje.textContent = '';
                return;
            }

            if (password.value === confirmar.value) {
                mensaje.textContent = '✓ Las contraseñas coinciden';
                mensaje.classList.remove('text-red-600');
                mensaje.classList.add('text-green-600');
            } else {
                mensaje.textContent = '✕ Las contraseñas no coinciden';
                mensaje.classList.remove('text-green-600');
                mensaje.classList.add('text-red-600');
            }
        }

        password.addEventListener('input', validarPasswords);
        confirmar.addEventListener('input', validarPasswords);

        document.getElementById('formCrearUsuario').addEventListener('submit', function(e) {
            if (password.value !== confirmar.value) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseñas diferentes',
                    text: 'La contraseña y su confirmación deben ser iguales.',
                    confirmButtonColor: '#16a34a'
                });
            }
        });
    </script>
@endsection
