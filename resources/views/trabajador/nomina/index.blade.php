@extends('layouts.worker')

@section('title', 'Mi Nómina — SystemCOFF 360')

@section('content')
    <!-- Encabezado -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-1">Mi cuenta</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">Mi Nómina</h1>
                <p class="text-gray-500 mt-1 text-sm">
                    Consulta todos los pagos que has recibido, {{ auth()->user()->name }}.
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </header>

    <!-- Métricas personales -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="bg-white rounded-3xl p-6 border border-green-100 hover:-translate-y-1 hover:shadow-lg transition-transform">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Total recibido</p>
                    <h3 class="text-4xl font-extrabold text-green-950">${{ number_format($totalRecibido, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Acumulado histórico</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-green-100 hover:-translate-y-1 hover:shadow-lg transition-transform">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Recibido este mes</p>
                    <h3 class="text-4xl font-extrabold text-green-950">${{ number_format($recibidoMes, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Mes en curso</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-green-100 hover:-translate-y-1 hover:shadow-lg transition-transform">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Pagos recibidos</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totalPagos }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Total de transacciones</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-green-100 hover:-translate-y-1 hover:shadow-lg transition-transform">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Último pago</p>
                    <h3 class="text-xl font-extrabold text-green-950">
                        {{ $ultimoPago ? date('d/m/Y', strtotime($ultimoPago)) : '—' }}
                    </h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Fecha del pago más reciente</p>
        </div>
    </section>

    <!-- Resumen por mes + Tabla detalle -->
    <div class="grid xl:grid-cols-3 gap-6">
        <!-- Resumen por mes -->
        <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                <i class="fas fa-chart-bar text-green-600"></i> Resumen por mes
            </h2>

            @if ($porMes->count() > 0)
                <div class="space-y-3">
                    @foreach ($porMes as $m)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-green-50 hover:bg-green-100 transition">
                            <div>
                                <p class="font-bold text-green-950 text-sm capitalize">{{ $m->mes_label }}</p>
                                <p class="text-xs text-gray-500">{{ $m->cantidad }} pago{{ $m->cantidad != 1 ? 's' : '' }}</p>
                            </div>
                            <span class="text-lg font-extrabold text-green-700">
                                ${{ number_format($m->total_mes, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                    Sin pagos registrados.
                </div>
            @endif
        </div>

        <!-- Tabla detalle de pagos -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-green-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-green-950">Detalle de pagos</h2>
                    <p class="text-sm text-gray-500">Historial completo de tus pagos recibidos.</p>
                </div>
                <!-- Buscador inline -->
                <input id="pagoSearch" type="text" placeholder="Buscar..." class="rounded-xl border border-green-200 px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300 w-40" />
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="tablaPagos">
                    <thead class="bg-green-50 text-green-900">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">Fecha</th>
                            <th class="px-5 py-4 text-left font-semibold">Tipo</th>
                            <th class="px-5 py-4 text-left font-semibold">Monto</th>
                            <th class="px-5 py-4 text-left font-semibold">Descripción</th>
                            <th class="px-5 py-4 text-left font-semibold">Registrado por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pagos as $p)
                            <tr class="border-b last:border-0 hover:bg-green-50/60 transition">
                                <td class="px-5 py-4 font-semibold text-green-950">
                                    {{ date('d/m/Y', strtotime($p->fecha)) }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold capitalize">
                                        {{ $p->tipo_pago }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-bold text-green-700 text-base">
                                    ${{ number_format($p->monto, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-gray-500 max-w-[200px] truncate" title="{{ $p->descripcion }}">
                                    {{ $p->descripcion ?? '—' }}
                                </td>
                                <td class="px-5 py-4 text-gray-400 text-xs">
                                    {{ $p->admin->name ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                    Aún no tienes pagos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script de búsqueda nativo -->
    <script>
    document.getElementById('pagoSearch').addEventListener('input', function () {
        const texto = this.value.trim().toLowerCase();
        document.querySelectorAll('#tablaPagos tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(texto) ? '' : 'none';
        });
    });
    </script>
@endsection
