@extends('layouts.admin')

@section('title', 'Nómina — SystemCOFF 360')

@section('content')
    <!-- Encabezado -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-1">Operaciones</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">Nómina</h1>
                <p class="text-gray-500 mt-1 text-sm">Gestiona el pago de jornales y salarios de los trabajadores de la finca.</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
        </div>
    </header>

    <!-- Métricas -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Trabajadores activos</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalTrabajadores }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Personal activo en la finca</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Pagos registrados</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalPagos }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Total de transacciones</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Pagado este mes</p>
                    <h3 class="text-4xl font-extrabold text-green-950">${{ number_format($pagadoMes, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Mes en curso</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Total pagado</p>
                    <h3 class="text-4xl font-extrabold text-green-950">${{ number_format($totalPagado, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Acumulado histórico</p>
        </div>
    </section>

    <!-- Buscador -->
    <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm mb-6">
        <p class="text-sm text-gray-500 mb-2">Buscar en nómina</p>
        <input id="nominaSearch" type="text"
               placeholder="Buscar por trabajador, tipo de pago, descripción..."
               class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" />
    </div>

    <!-- Formulario + Tabla -->
    <section class="grid xl:grid-cols-3 gap-6">

        <!-- Formulario registrar pago -->
        <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                <i class="fas fa-plus-circle text-green-600"></i> Registrar pago
            </h2>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('nomina.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Trabajador</label>
                    <select class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" name="id_trabajador" required>
                        <option value="">Seleccione trabajador</option>
                        @foreach ($trabajadores as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de pago</label>
                    <select class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" name="tipo_pago" required>
                        <option value="jornal">Jornal</option>
                        <option value="quincenal">Quincenal</option>
                        <option value="mensual">Mensual</option>
                        <option value="bono">Bono</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Monto ($)</label>
                    <input class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="number" step="0.01" min="0.01" name="monto" placeholder="Valor a pagar" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de pago</label>
                    <input class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="date" name="fecha_pago" value="{{ date('Y-m-d') }}" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción / Observaciones</label>
                    <textarea class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                              name="descripcion" rows="3" placeholder="Ej: Pago semana del 19 al 23 de mayo..."></textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Registrar pago
                </button>
            </form>
        </div>

        <!-- Tabla historial -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-green-100">
                <h2 class="text-xl font-extrabold text-green-950">Historial de pagos</h2>
                <p class="text-sm text-gray-500">Todos los pagos registrados a trabajadores de la finca.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tablaNomina">
                    <thead class="bg-green-50 text-green-900">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">Trabajador</th>
                            <th class="px-5 py-4 text-left font-semibold">Fecha</th>
                            <th class="px-5 py-4 text-left font-semibold">Tipo</th>
                            <th class="px-5 py-4 text-left font-semibold">Monto</th>
                            <th class="px-5 py-4 text-left font-semibold">Descripción</th>
                            <th class="px-5 py-4 text-left font-semibold">Registrado por</th>
                            <th class="px-5 py-4 text-left font-semibold">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pagos as $p)
                            <tr class="border-b last:border-0 hover:bg-green-50/60 transition">
                                <td class="px-5 py-4 font-bold text-green-950">{{ $p->trabajador->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $p->fecha }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold capitalize">
                                        {{ $p->tipo_pago }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-bold text-green-700">${{ number_format($p->monto, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-gray-500 max-w-[180px] truncate" title="{{ $p->descripcion }}">
                                    {{ $p->descripcion ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-gray-500 text-xs">{{ $p->admin->name ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <form action="{{ route('nomina.destroy', $p->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este pago?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                    No hay pagos registrados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Script para buscador local -->
    <script>
        document.getElementById('nominaSearch').addEventListener('input', function () {
            const texto = this.value.trim().toLowerCase();
            document.querySelectorAll('#tablaNomina tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });
    </script>
@endsection
