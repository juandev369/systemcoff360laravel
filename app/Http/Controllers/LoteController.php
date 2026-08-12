<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    public function index()
    {
        // Traemos todos los lotes de la base de datos
        $lotes = Lote::all();
        
        // Calculamos las métricas usando colecciones de Laravel
        $totalLotes = $lotes->count();
        $totalActivos = $lotes->where('estado', 'activo')->count();
        $totalArea = $lotes->sum('area_hectareas');

        // Enviamos todo a la vista
        return view('admin.lotes.index', compact('lotes', 'totalLotes', 'totalActivos', 'totalArea'));
    }

    public function create()
    {
        return view('admin.lotes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:80',
            'tipo_plantacion' => 'required|string|max:80',
            'ubicacion' => 'nullable|string',
            'area_hectareas' => 'nullable|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Lote::create($validated);

        return redirect()->route('lotes.index')->with('alert', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Lote creado exitosamente.'
        ]);
    }

    public function show(Lote $lote)
    {
        return view('admin.lotes.show', compact('lote'));
    }

    public function edit(Lote $lote)
    {
        return view('admin.lotes.edit', compact('lote'));
    }

    public function update(Request $request, Lote $lote)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:80',
            'tipo_plantacion' => 'required|string|max:80',
            'ubicacion' => 'nullable|string',
            'area_hectareas' => 'nullable|numeric|min:0',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $lote->update($validated);

        return redirect()->route('lotes.index')->with('alert', [
            'icon' => 'success',
            'title' => '¡Actualizado!',
            'text' => 'Lote actualizado exitosamente.'
        ]);
    }

    public function destroy(Lote $lote)
    {
        $lote->delete();

        return redirect()->route('lotes.index')->with('alert', [
            'icon' => 'success',
            'title' => '¡Eliminado!',
            'text' => 'Lote eliminado exitosamente.'
        ]);
    }
}