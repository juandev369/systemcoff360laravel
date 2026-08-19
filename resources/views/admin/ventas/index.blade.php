@extends('layouts.admin')

@section('title', 'Ventas — SystemCOFF 360')

@section('content')
    <!-- Encabezado -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-1">Producción</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">Ventas</h1>
                <p class="text-gray-500 mt-1 text-sm">Registra las ventas de cosechas y consulta la trazabilidad de cada transacción.</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg flex-shrink-0">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
    </header>

    <!-- Métricas -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Ventas registradas</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ $totales['ventas'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Total de transacciones realizadas</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Kg vendidos</p>
                    <h3 class="text-4xl font-extrabold text-green-950">{{ number_format($totales['kgs'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fas fa-weight text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Kilogramos de café despachados</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Ingresos totales</p>
                    <h3 class="text-4xl font-extrabold text-green-950">${{ number_format($totales['ingresos'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Valor acumulado de ventas</p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-sm text-gray-500">Fuente de venta</p>
                    <h3 class="text-2xl font-extrabold text-green-950">Cosechas</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-lime-100 text-lime-700 flex items-center justify-center">
                    <i class="fas fa-leaf text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500">Trazabilidad desde lote y cosecha</p>
        </div>
    </section>

    <!-- Buscador -->
    <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm mb-6">
        <p class="text-sm text-gray-500 mb-2">Buscar ventas</p>
        <input id="ventasSearch" type="text"
               placeholder="Buscar por comprador, lote, tipo de café..."
               class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" />
    </div>

    <!-- Formulario + Tabla -->
    <section class="grid xl:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                <i class="fas fa-plus-circle text-green-600"></i> Registrar venta
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

            <form action="{{ route('ventas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cosecha</label>
                    <select class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" name="cosecha_id" required>
                        <option value="">Seleccione cosecha</option>
                        @foreach ($cosechas as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->lote_nombre ?? 'Sin lote' }} — {{ number_format($c->cantidad_kg, 2, ',', '.') }} kg — {{ $c->fecha }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de venta</label>
                    <input class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad vendida (kg)</label>
                    <input id="cantidadVenta"
                           class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="number" step="0.01" min="0.01" name="cantidad_kg" value="{{ old('cantidad_kg') }}" placeholder="Cantidad" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Precio por kg</label>
                    <input id="precioVenta"
                           class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="number" step="0.01" min="0" name="precio_kg" value="{{ old('precio_kg') }}" placeholder="Precio por kg" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Comprador</label>
                    <input class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                           type="text" name="comprador" value="{{ old('comprador') }}" placeholder="Nombre del comprador" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de café</label>
                    <select class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300" name="tipo_cafe" required>
                        <option value="pergamino" {{ old('tipo_cafe') == 'pergamino' ? 'selected' : '' }}>Pergamino</option>
                        <option value="lavado" {{ old('tipo_cafe') == 'lavado' ? 'selected' : '' }}>Lavado</option>
                        <option value="natural" {{ old('tipo_cafe') == 'natural' ? 'selected' : '' }}>Natural</option>
                        <option value="miel" {{ old('tipo_cafe') == 'miel' ? 'selected' : '' }}>Miel</option>
                        <option value="orgánico" {{ old('tipo_cafe') == 'orgánico' ? 'selected' : '' }}>Orgánico</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Observaciones</label>
                    <textarea class="w-full rounded-2xl border border-green-200 px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-green-300"
                              name="observaciones" rows="3" placeholder="Detalles adicionales">{{ old('observaciones') }}</textarea>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-green-50 px-4 py-3">
                    <span class="text-sm text-gray-600">Total estimado</span>
                    <span id="ventaTotal" class="text-lg font-extrabold text-green-700">$0.00</span>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Registrar venta
                </button>
            </form>
        </div>

        <!-- Tabla historial -->
        <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-green-100">
                <h2 class="text-xl font-extrabold text-green-950">Historial de ventas</h2>
                <p class="text-sm text-gray-500">Revisa las ventas registradas con trazabilidad de cosecha y lote.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-green-50 text-green-900">
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">Fecha</th>
                            <th class="px-5 py-4 text-left font-semibold">Lote</th>
                            <th class="px-5 py-4 text-left font-semibold">F. Cosecha</th>
                            <th class="px-5 py-4 text-left font-semibold">Cantidad</th>
                            <th class="px-5 py-4 text-left font-semibold">Precio/kg</th>
                            <th class="px-5 py-4 text-left font-semibold">Total</th>
                            <th class="px-5 py-4 text-left font-semibold">Comprador</th>
                            <th class="px-5 py-4 text-left font-semibold">Tipo café</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ventas as $v)
                            <tr class="border-b last:border-0 hover:bg-green-50/60 transition">
                                <td class="px-5 py-4">{{ $v->fecha }}</td>
                                <td class="px-5 py-4 font-bold text-green-950">{{ $v->lote_nombre ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $v->fecha_cosecha ?? '—' }}</td>
                                <td class="px-5 py-4">{{ number_format($v->cantidad_kg, 2) }} kg</td>
                                <td class="px-5 py-4">${{ number_format($v->precio_kg, 2, ',', '.') }}</td>
                                <td class="px-5 py-4 font-bold text-green-700">${{ number_format($v->total, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">{{ $v->comprador }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                        {{ $v->tipo_cafe }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                    No hay ventas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Scripts locales para la vista -->
    <script>
        function actualizarTotalVenta() {
            const cantidad = parseFloat(document.getElementById('cantidadVenta').value) || 0;
            const precio   = parseFloat(document.getElementById('precioVenta').value)   || 0;
            document.getElementById('ventaTotal').textContent =
                '$' + (cantidad * precio).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        document.getElementById('cantidadVenta').addEventListener('input', actualizarTotalVenta);
        document.getElementById('precioVenta').addEventListener('input',   actualizarTotalVenta);

        document.getElementById('ventasSearch').addEventListener('input', function () {
            const texto = this.value.trim().toLowerCase();
            document.querySelectorAll('table tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });
    </script>
@endsection
