<?php

namespace App\Http\Controllers;

use App\Models\PagoNomina;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoNominaController extends Controller
{
    public function index()
    {
        // Métricas
        $totalTrabajadores = User::where('role_id', 2)->where('estado', 'activo')->count();
        $totalPagado       = PagoNomina::sum('monto');
        $pagadoMes         = PagoNomina::whereMonth('fecha', now()->month)
                                       ->whereYear('fecha', now()->year)
                                       ->sum('monto');
        $totalPagos        = PagoNomina::count();

        // Trabajadores para el select (solo activos)
        $trabajadores = User::where('role_id', 2)->where('estado', 'activo')->orderBy('name')->get();

        // Historial de pagos con las relaciones (trabajador y admin) cargadas
        $pagos = PagoNomina::with(['trabajador', 'admin'])
                           ->orderBy('fecha', 'desc')
                           ->orderBy('id', 'desc')
                           ->get();

        return view('admin.nomina.index', compact(
            'totalTrabajadores', 'totalPagado', 'pagadoMes', 'totalPagos', 'trabajadores', 'pagos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trabajador' => 'required|integer',
            'tipo_pago'     => 'required|string',
            'monto'         => 'required|numeric|min:0.01',
            'fecha_pago'    => 'required|date',
            'descripcion'   => 'nullable|string',
        ]);

        PagoNomina::create([
            'user_id'        => $request->id_trabajador,
            'registrado_por' => auth()->id(), // Laravel saca automáticamente el ID del admin conectado
            'fecha'          => $request->fecha_pago,
            'monto'          => $request->monto,
            'tipo_pago'      => $request->tipo_pago,
            'descripcion'    => $request->descripcion,
        ]);

        return redirect()->route('nomina.index')->with('alert', [
            'icon'  => 'success',
            'title' => 'Pago registrado',
            'text'  => 'El pago se asignó correctamente al trabajador.'
        ]);
    }

    public function destroy($id)
    {
        PagoNomina::findOrFail($id)->delete();

        return redirect()->route('nomina.index')->with('alert', [
            'icon'  => 'success',
            'title' => 'Pago eliminado',
            'text'  => 'El registro de pago fue eliminado.'
        ]);
    }
}
