<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class InventarioController extends Controller
{
    public function index()
    {
        // Consultas directas a la base de datos simulando el modelo original
        $insumos = DB::table('insumo')->get();
        $herramientas = DB::table('herramienta')->get();
        $epps = DB::table('epp')->get();
        $trabajadores = User::where('role_id', 2)->where('estado', 'activo')->get();

        // Cálculo de métricas
        $totales = [
            'insumos' => $insumos->count(),
            'insumos_bajos' => $insumos->where('stock_actual', '<=', 'stock_minimo')->count(),
            'herramientas' => $herramientas->count(),
            'herramientas_disponibles' => $herramientas->where('estado', 'disponible')->count(),
            'epp' => $epps->count(),
            'epp_bajo' => $epps->where('stock_disponible', '<=', 2)->count(),
        ];

        // Filtros para la vista
        $tiposInsumos = $insumos->pluck('tipo')->unique()->values();
        $estadosHerramientas = $herramientas->pluck('estado')->map(function($estado) {
            return ucfirst(str_replace('_', ' ', $estado));
        })->unique()->values();

        return view('admin.inventario.index', compact(
            'insumos', 'herramientas', 'epps', 'trabajadores', 'totales', 'tiposInsumos', 'estadosHerramientas'
        ));
    }

    public function storeInsumo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|string|in:fertilizante,pesticida,herbicida,fungicida,abono_organico,enmienda,otro',
            'unidad' => 'required|string|in:kg,L,bulto,g,ml,unidad',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'precio_unidad' => 'required|numeric|min:0',
            'ubicacion_bodega' => 'nullable|string|max:100',
        ]);

        DB::table('insumo')->insert([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'unidad' => $request->unidad,
            'stock_actual' => $request->stock_actual,
            'stock_minimo' => $request->stock_minimo,
            'precio_unidad' => $request->precio_unidad,
            'ubicacion_bodega' => $request->ubicacion_bodega,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('inventario.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Insumo registrado',
            'text' => 'El insumo se ha guardado correctamente en el inventario.'
        ]);
    }

    public function storeHerramienta(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string|in:disponible,prestada,en_mantenimiento,baja',
        ]);

        DB::table('herramienta')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('inventario.index')->with('alert', [
            'icon' => 'success',
            'title' => 'Herramienta registrada',
            'text' => 'La herramienta se ha guardado correctamente en el inventario.'
        ]);
    }

    public function storeEpp(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'cantidad_total' => 'required|integer|min:0',
            'stock_disponible' => 'required|integer|min:0|lte:cantidad_total',
        ], [
            'stock_disponible.lte' => 'El stock disponible no puede superar la cantidad total.',
        ]);

        DB::table('epp')->insert([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'cantidad_total' => $request->cantidad_total,
            'stock_disponible' => $request->stock_disponible,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('inventario.index')->with('alert', [
            'icon' => 'success',
            'title' => 'EPP registrado',
            'text' => 'El EPP se ha guardado correctamente en el inventario.'
        ]);
    }
}
