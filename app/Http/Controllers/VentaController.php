<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        // Traemos las ventas uniendo la tabla cosecha y lotes para la trazabilidad
        $ventas = DB::table('ventas')
            ->leftJoin('cosecha', 'ventas.cosecha_id', '=', 'cosecha.id')
            ->leftJoin('lotes', 'cosecha.lote_id', '=', 'lotes.id')
            ->select('ventas.*', 'cosecha.fecha as fecha_cosecha', 'lotes.nombre as lote_nombre')
            ->orderBy('ventas.fecha', 'desc')
            ->get();

        // Para el select del formulario
        $cosechas = DB::table('cosecha')
            ->leftJoin('lotes', 'cosecha.lote_id', '=', 'lotes.id')
            ->select('cosecha.*', 'lotes.nombre as lote_nombre')
            ->orderBy('cosecha.fecha', 'desc')
            ->get();

        // Cálculos de métricas
        $totales = [
            'ventas'   => $ventas->count(),
            'kgs'      => $ventas->sum('cantidad_kg'),
            'ingresos' => $ventas->sum('total'),
        ];

        return view('admin.ventas.index', compact('ventas', 'cosechas', 'totales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cosecha_id'  => 'required|integer',
            'fecha'       => 'required|date',
            'cantidad_kg' => 'required|numeric|min:0.01',
            'precio_kg'   => 'required|numeric|min:0',
            'comprador'   => 'required|string|max:150',
            'tipo_cafe'   => 'required|string|max:50',
            'observaciones'=> 'nullable|string',
        ]);

        // Calculamos el total antes de guardar
        $total = $request->cantidad_kg * $request->precio_kg;

        Venta::create([
            'cosecha_id'  => $request->cosecha_id,
            'fecha'       => $request->fecha,
            'cantidad_kg' => $request->cantidad_kg,
            'precio_kg'   => $request->precio_kg,
            'total'       => $total,
            'comprador'   => $request->comprador,
            'tipo_cafe'   => $request->tipo_cafe,
            'observaciones'=> $request->observaciones,
        ]);

        return redirect()->route('ventas.index')->with('alert', [
            'icon'  => 'success',
            'title' => 'Venta registrada',
            'text'  => 'La transacción se guardó correctamente.'
        ]);
    }
}
