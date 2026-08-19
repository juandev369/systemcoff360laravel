@extends('layouts.admin')

@section('title', 'Entregas — SystemCOFF 360')

@section('content')
    <!-- HEADER -->
    <header class="bg-white rounded-3xl p-6 shadow-sm border border-green-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <p class="text-green-700 font-bold uppercase tracking-widest text-xs mb-2">Gestión de finca</p>
                <h1 class="text-3xl md:text-4xl font-extrabold text-green-950">Entregas</h1>
                <p class="text-gray-500 mt-2">Administra la entrega y devolución de herramientas y EPP.</p>
            </div>
            <div class="w-16 h-16 rounded-2xl bg-green-600 text-white flex items-center justify-center shadow-lg">
                <i class="fas fa-truck-loading text-2xl"></i>
            </div>
        </div>
    </header>

    <!-- FORMULARIOS -->
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <!-- ENTREGAR HERRAMIENTA -->
        <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h2 class="text-xl font-extrabold text-green-950 mb-4">Entregar herramienta</h2>
            <form action="{{ route('entregas.herramienta.store') }}" method="POST" class="space-y-4">
                @csrf
                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="id_herramienta" required>
                    <option value="">Herramienta disponible</option>
                    @foreach ($herramientas as $h)
                        @if ($h->estado === 'disponible')
                            <option value="{{ $h->id }}">{{ $h->nombre }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="id_usuario" required>
                    <option value="">Trabajador</option>
                    @foreach ($trabajadores as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>

                <input class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" type="date" name="fecha_entrega" value="{{ date('Y-m-d') }}" required>

                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="estado_herramienta">
                    <option value="bueno">Bueno</option>
                    <option value="regular">Regular</option>
                    <option value="malo">Malo</option>
                </select>

                <textarea class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="observaciones" placeholder="Observaciones"></textarea>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-bold transition">
                    <i class="fas fa-hand-holding mr-2"></i> Entregar herramienta
                </button>
            </form>
        </div>

        <!-- ENTREGAR EPP -->
        <div class="bg-white rounded-3xl p-6 border border-green-100 shadow-sm">
            <h2 class="text-xl font-extrabold text-green-950 mb-4">Entregar EPP</h2>
            <form action="{{ route('entregas.epp.store') }}" method="POST" class="space-y-4">
                @csrf
                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="id_epp" required>
                    <option value="">Seleccione EPP</option>
                    @foreach ($epps as $e)
                        @if ($e->stock_disponible > 0)
                            <option value="{{ $e->id }}">{{ $e->nombre }} — Disp: {{ $e->stock_disponible }}</option>
                        @endif
                    @endforeach
                </select>

                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="id_usuario" required>
                    <option value="">Trabajador</option>
                    @foreach ($trabajadores as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>

                <input class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" type="date" name="fecha_entrega" value="{{ date('Y-m-d') }}" required>

                <select class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="estado_elemento">
                    <option value="bueno">Bueno</option>
                    <option value="regular">Regular</option>
                    <option value="malo">Malo</option>
                </select>

                <textarea class="w-full border border-green-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 outline-none" name="observaciones" placeholder="Observaciones"></textarea>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-bold transition">
                    <i class="fas fa-hand-holding-medical mr-2"></i> Entregar EPP
                </button>
            </form>
        </div>
    </section>

    <!-- LISTADO ENTREGAS -->
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- TABLA HERRAMIENTAS -->
        <div class="bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-green-100"><h2 class="text-xl font-extrabold text-green-950">Entregas de herramientas</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-green-50 text-green-900">
                        <tr><th class="px-5 py-4 text-left">Herramienta</th><th class="px-5 py-4 text-left">Trabajador</th><th class="px-5 py-4 text-left">Fecha</th><th class="px-5 py-4 text-right">Acción</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($entregasHerramientas as $eh)
                            <tr class="border-b last:border-0 hover:bg-green-50/50">
                                <td class="px-5 py-4 font-bold">{{ $eh->herramienta }}</td>
                                <td class="px-5 py-4">{{ $eh->trabajador }}</td>
                                <td class="px-5 py-4">
                                    {{ $eh->fecha_entrega }}
                                    @if ($eh->fecha_devolucion)
                                        <p class="text-xs text-green-600">Devuelta: {{ $eh->fecha_devolucion }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if (!$eh->fecha_devolucion)
                                        <form action="{{ route('entregas.herramienta.return', $eh->id) }}" method="POST" onsubmit="return confirm('¿Confirmas la devolución de esta herramienta?')">
                                            @csrf @method('PATCH')
                                            <button class="px-3 py-2 rounded-xl bg-green-50 text-green-700 hover:bg-green-100">Devolver</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Finalizada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">No hay entregas de herramientas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABLA EPP -->
        <div class="bg-white rounded-3xl border border-green-100 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-green-100"><h2 class="text-xl font-extrabold text-green-950">Entregas de EPP</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-green-50 text-green-900">
                        <tr><th class="px-5 py-4 text-left">EPP</th><th class="px-5 py-4 text-left">Trabajador</th><th class="px-5 py-4 text-left">Fecha</th><th class="px-5 py-4 text-right">Acción</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($entregasEpp as $ee)
                            <tr class="border-b last:border-0 hover:bg-green-50/50">
                                <td class="px-5 py-4 font-bold">{{ $ee->epp }}</td>
                                <td class="px-5 py-4">{{ $ee->trabajador }}</td>
                                <td class="px-5 py-4">
                                    {{ $ee->fecha_entrega }}
                                    @if ($ee->fecha_devolucion)
                                        <p class="text-xs text-green-600">Devuelto: {{ $ee->fecha_devolucion }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if (!$ee->fecha_devolucion)
                                        <form action="{{ route('entregas.epp.return', $ee->id) }}" method="POST" onsubmit="return confirm('¿Confirmas la devolución de este EPP?')">
                                            @csrf @method('PATCH')
                                            <button class="px-3 py-2 rounded-xl bg-green-50 text-green-700 hover:bg-green-100">Devolver</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">Finalizada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">No hay entregas de EPP.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
