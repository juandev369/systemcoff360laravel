@extends('layouts.admin')

@section('title', 'Inventario — SystemCOFF 360')

@section('content')
    <!-- HEADER -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">Gestión de finca</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">Inventario</h1>
                <p class="text-gray-500 mt-2">Control de insumos, herramientas, EPP y alertas de stock.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg">
                    <i class="fas fa-warehouse text-2xl"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- ALERTAS DE VALIDACIÓN -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl">
            <h4 class="font-bold mb-2">Corrija los siguientes errores:</h4>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- TARJETAS -->
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Insumos Registrados</p>
                    <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totales['insumos'] }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center">
                    <i class="fas fa-flask text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-4 font-semibold flex items-center gap-1">
                <i class="fas fa-exclamation-triangle"></i> {{ $totales['insumos_bajos'] }} con stock bajo
            </p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Herramientas Totales</p>
                    <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totales['herramientas'] }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center">
                    <i class="fas fa-tools text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-4 font-semibold flex items-center gap-1">
                <i class="fas fa-check-circle"></i> {{ $totales['herramientas_disponibles'] }} disponibles
            </p>
        </div>

        <div class="card-hover bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500 font-medium">EPP Registrados</p>
                    <h3 class="text-4xl font-extrabold text-green-950 mt-2">{{ $totales['epp'] }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center">
                    <i class="fas fa-hard-hat text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-red-600 mt-4 font-semibold flex items-center gap-1">
                <i class="fas fa-exclamation-triangle"></i> {{ $totales['epp_bajo'] }} con stock bajo (≤ 2)
            </p>
        </div>
    </section>

    <!-- GRÁFICAS -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <div class="xl:col-span-2 chart-card rounded-3xl p-6 border border-green-100 shadow-sm bg-white">
            <h2 class="text-xl font-extrabold text-green-950 mb-6">Estado general del inventario</h2>
            <div class="h-[300px] flex items-center justify-center">
                <canvas id="graficaInventario"></canvas>
            </div>
        </div>
        <div class="chart-card rounded-3xl p-6 border border-green-100 shadow-sm bg-white">
            <h2 class="text-xl font-extrabold text-green-950 mb-6">Stock crítico / Alertas</h2>
            <div class="h-[300px] flex items-center justify-center">
                <canvas id="graficaAlertas"></canvas>
            </div>
        </div>
    </section>

    <!-- TABS DE NAVEGACIÓN -->
    <section class="bg-white rounded-3xl p-3 border border-green-100 mb-8 shadow-sm">
        <div class="flex flex-wrap gap-2">
            <button class="tab-btn active px-6 py-3 rounded-2xl font-bold bg-green-600 text-white transition flex items-center gap-2" onclick="openTab('insumos', this)">
                <i class="fas fa-flask"></i> Insumos
            </button>
            <button class="tab-btn px-6 py-3 rounded-2xl font-bold bg-green-50 text-green-700 hover:bg-green-100 transition flex items-center gap-2" onclick="openTab('herramientas', this)">
                <i class="fas fa-tools"></i> Herramientas
            </button>
            <button class="tab-btn px-6 py-3 rounded-2xl font-bold bg-green-50 text-green-700 hover:bg-green-100 transition flex items-center gap-2" onclick="openTab('epp', this)">
                <i class="fas fa-hard-hat"></i> EPP
            </button>
        </div>
    </section>

    <!-- PANEL INSUMOS -->
    <section id="insumos" class="panel active">
        <div class="grid xl:grid-cols-3 gap-6">
            <!-- Formulario Insumos -->
            <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm self-start">
                <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600"></i> Nuevo Insumo
                </h2>
                <form action="{{ route('inventario.insumo.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                        <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600" type="text" name="nombre" placeholder="Ej: Urea 46%" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Insumo</label>
                        <select class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600" name="tipo" required>
                            <option value="fertilizante">Fertilizante</option>
                            <option value="pesticida">Pesticida</option>
                            <option value="herbicida">Herbicida</option>
                            <option value="fungicida">Fungicida</option>
                            <option value="abono_organico">Abono Orgánico</option>
                            <option value="enmienda">Enmienda</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Unidad</label>
                            <select class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600 focus:ring-1 focus:ring-green-600" name="unidad" required>
                                <option value="kg">kg</option>
                                <option value="L">L</option>
                                <option value="bulto">Bulto</option>
                                <option value="g">g</option>
                                <option value="ml">ml</option>
                                <option value="unidad">Unidad</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Precio Unit. ($)</label>
                            <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="number" step="0.01" min="0" name="precio_unidad" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Actual</label>
                            <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="number" step="0.01" min="0" name="stock_actual" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Mínimo</label>
                            <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="number" step="0.01" min="0" name="stock_minimo" placeholder="0.00" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Ubicación Bodega</label>
                        <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="text" name="ubicacion_bodega" placeholder="Ej: Estante A-1">
                    </div>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Guardar Insumo
                    </button>
                </form>
            </div>
            <!-- Tabla Insumos -->
            <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm self-start">
                <div class="p-6 border-b border-green-100">
                    <h2 class="text-xl font-bold text-green-950">Lista de Insumos</h2>
                    <p class="text-sm text-gray-500">Insumos y agroquímicos almacenados en bodega.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-green-50 text-green-900">
                            <tr>
                                <th class="px-5 py-4 text-left font-semibold">Insumo</th>
                                <th class="px-5 py-4 text-left font-semibold">Tipo</th>
                                <th class="px-5 py-4 text-left font-semibold">Stock Actual</th>
                                <th class="px-5 py-4 text-left font-semibold">Ubicación</th>
                                <th class="px-5 py-4 text-left font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($insumos as $i)
                                <tr class="border-b last:border-0 hover:bg-green-50/50 transition">
                                    <td class="px-5 py-4 font-bold text-green-950">
                                        {{ $i->nombre }}
                                        <p class="text-xs font-normal text-gray-400">${{ number_format($i->precio_unidad, 0, ',', '.') }} / {{ $i->unidad }}</p>
                                    </td>
                                    <td class="px-5 py-4 capitalize text-gray-600">{{ str_replace('_', ' ', $i->tipo) }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-700">{{ number_format($i->stock_actual, 2) }} {{ $i->unidad }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $i->ubicacion_bodega ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        @if ($i->stock_actual <= $i->stock_minimo)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-exclamation-circle text-2xs"></i> Stock bajo
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-check-circle text-2xs"></i> Normal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl mb-3 block"></i> No hay insumos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- PANEL HERRAMIENTAS -->
    <section id="herramientas" class="panel hidden">
        <div class="grid xl:grid-cols-3 gap-6">
            <!-- Formulario Herramientas -->
            <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm self-start">
                <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600"></i> Nueva Herramienta
                </h2>
                <form action="{{ route('inventario.herramienta.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de la Herramienta</label>
                        <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="text" name="nombre" placeholder="Ej: Motoguadaña STIHL" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estado Inicial</label>
                        <select class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" name="estado" required>
                            <option value="disponible">Disponible</option>
                            <option value="prestada">Prestada</option>
                            <option value="en_mantenimiento">En Mantenimiento</option>
                            <option value="baja">Baja / Descarte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción / Observaciones</label>
                        <textarea class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" name="descripcion" rows="3" placeholder="Detalles de marca, estado, serie..."></textarea>
                    </div>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Guardar Herramienta
                    </button>
                </form>
            </div>
            <!-- Tabla Herramientas -->
            <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm self-start">
                <div class="p-6 border-b border-green-100">
                    <h2 class="text-xl font-bold text-green-950">Lista de Herramientas</h2>
                    <p class="text-sm text-gray-500">Herramientas y maquinaria de trabajo en campo.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-green-50 text-green-900">
                            <tr>
                                <th class="px-5 py-4 text-left font-semibold">Herramienta</th>
                                <th class="px-5 py-4 text-left font-semibold">Descripción</th>
                                <th class="px-5 py-4 text-left font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($herramientas as $h)
                                <tr class="border-b last:border-0 hover:bg-green-50/50 transition">
                                    <td class="px-5 py-4 font-bold text-green-950">{{ $h->nombre }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $h->descripcion ?? '—' }}</td>
                                    <td class="px-5 py-4">
                                        @if ($h->estado === 'disponible')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </span>
                                        @elseif ($h->estado === 'prestada')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-hand-holding"></i> Prestada
                                            </span>
                                        @elseif ($h->estado === 'en_mantenimiento')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-wrench"></i> En Mantenimiento
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 flex items-center gap-1 w-fit">
                                                <i class="fas fa-trash-alt"></i> De Baja
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-12 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl mb-3 block"></i> No hay herramientas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- PANEL EPP -->
    <section id="epp" class="panel hidden">
        <div class="grid xl:grid-cols-3 gap-6">
            <!-- Formulario EPP -->
            <div class="xl:col-span-1 bg-white rounded-3xl p-6 border border-green-100 shadow-sm self-start">
                <h2 class="text-xl font-extrabold text-green-950 mb-5 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600"></i> Nuevo EPP
                </h2>
                <form action="{{ route('inventario.epp.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Elemento</label>
                        <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="text" name="nombre" placeholder="Ej: Caretas de protección" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Cantidad Total</label>
                            <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="number" min="0" name="cantidad_total" placeholder="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Disponible</label>
                            <input class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" type="number" min="0" name="stock_disponible" placeholder="0" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción / Observaciones</label>
                        <textarea class="w-full border border-green-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-green-600" name="descripcion" rows="3" placeholder="Detalles de talla, material, uso..."></textarea>
                    </div>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-bold transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Guardar EPP
                    </button>
                </form>
            </div>
            <!-- Tabla EPP -->
            <div class="xl:col-span-2 bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm self-start">
                <div class="p-6 border-b border-green-100">
                    <h2 class="text-xl font-bold text-green-950">Elementos de Protección Personal (EPP)</h2>
                    <p class="text-sm text-gray-500">Dotación de seguridad y protección para trabajadores.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-green-50 text-green-900">
                            <tr>
                                <th class="px-5 py-4 text-left font-semibold">Elemento</th>
                                <th class="px-5 py-4 text-left font-semibold">Descripción</th>
                                <th class="px-5 py-4 text-left font-semibold">Inventario (Total)</th>
                                <th class="px-5 py-4 text-left font-semibold">Disponible</th>
                                <th class="px-5 py-4 text-left font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($epps as $e)
                                <tr class="border-b last:border-0 hover:bg-green-50/50 transition">
                                    <td class="px-5 py-4 font-bold text-green-950">{{ $e->nombre }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $e->descripcion ?? '—' }}</td>
                                    <td class="px-5 py-4 text-slate-700 font-semibold">{{ $e->cantidad_total }} unds</td>
                                    <td class="px-5 py-4 text-green-700 font-bold">{{ $e->stock_disponible }} unds</td>
                                    <td class="px-5 py-4">
                                        @if ($e->stock_disponible <= 2)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-exclamation-circle"></i> Stock crítico
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 flex items-center gap-1 w-fit">
                                                <i class="fas fa-check-circle"></i> Abundante
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                        <i class="fas fa-inbox text-3xl mb-3 block"></i> No hay EPP registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function openTab(id, btn) {
            document.querySelectorAll('.panel').forEach(panel => panel.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(button => {
                button.classList.remove('active', 'bg-green-600', 'text-white');
                button.classList.add('bg-green-50', 'text-green-700');
            });
            document.getElementById(id).classList.remove('hidden');
            btn.classList.remove('bg-green-50', 'text-green-700');
            btn.classList.add('active', 'bg-green-600', 'text-white');
        }

        // Datos inyectados desde Laravel para Chart.js
        const inventarioData = @json($totales);

        new Chart(document.getElementById('graficaInventario'), {
            type: 'bar',
            data: {
                labels: ['Insumos', 'Herramientas', 'EPP'],
                datasets: [{
                    label: 'Total registrado',
                    data: [inventarioData.insumos, inventarioData.herramientas, inventarioData.epp],
                    backgroundColor: ['#16a34a', '#3b82f6', '#eab308'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('graficaAlertas'), {
            type: 'doughnut',
            data: {
                labels: ['Insumos bajos', 'EPP bajo', 'Normal'],
                datasets: [{
                    data: [
                        inventarioData.insumos_bajos, 
                        inventarioData.epp_bajo, 
                        Math.max(0, (inventarioData.insumos + inventarioData.epp) - (inventarioData.insumos_bajos + inventarioData.epp_bajo))
                    ],
                    backgroundColor: ['#ef4444', '#eab308', '#16a34a']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
