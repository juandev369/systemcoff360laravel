<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminEntregaController extends Controller
{
    public function index()
    {
        $trabajadores = User::where('role_id', 2)->where('estado', 'activo')->get();
        $herramientas = DB::table('herramienta')->get();
        $epps = DB::table('epp')->get();

        // Entregas de Herramientas con JOINs para ver nombres
        $entregasHerramientas = DB::table('entrega_herramientas as eh')
            ->join('herramienta as h', 'eh.herramienta_id', '=', 'h.id')
            ->join('users as u', 'eh.user_id', '=', 'u.id')
            ->select('eh.*', 'h.nombre as herramienta', 'u.name as trabajador')
            ->orderBy('eh.fecha_entrega', 'desc')
            ->get();

        // Entregas de EPP con JOINs
        $entregasEpp = DB::table('entrega_epps as ee')
            ->join('epp as e', 'ee.epp_id', '=', 'e.id')
            ->join('users as u', 'ee.user_id', '=', 'u.id')
            ->select('ee.*', 'e.nombre as epp', 'u.name as trabajador')
            ->orderBy('ee.fecha_entrega', 'desc')
            ->get();

        return view('admin.entregas.index', compact('trabajadores', 'herramientas', 'epps', 'entregasHerramientas', 'entregasEpp'));
    }

    public function storeHerramienta(Request $request)
    {
        $request->validate([
            'id_herramienta' => 'required|integer',
            'id_usuario' => 'required|integer',
            'fecha_entrega' => 'required|date',
            'estado_herramienta' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Crear el registro de entrega
            DB::table('entrega_herramientas')->insert([
                'herramienta_id'     => $request->id_herramienta,
                'user_id'            => $request->id_usuario,
                'fecha_entrega'      => $request->fecha_entrega,
                'estado_herramienta' => $request->estado_herramienta,
                'observaciones'      => $request->observaciones,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
            // 2. Cambiar estado de la herramienta a "prestada" (en uso/prestada)
            DB::table('herramienta')->where('id', $request->id_herramienta)->update([
                'estado' => 'prestada',
                'updated_at' => now(),
            ]);
        });

        return back()->with('alert', ['icon' => 'success', 'title' => 'Entregada', 'text' => 'Herramienta asignada correctamente.']);
    }

    public function returnHerramienta($id)
    {
        DB::transaction(function () use ($id) {
            $entrega = DB::table('entrega_herramientas')->where('id', $id)->first();
            
            // 1. Marcar fecha de devolución
            DB::table('entrega_herramientas')->where('id', $id)->update([
                'fecha_devolucion' => now(),
                'updated_at' => now(),
            ]);
            // 2. Liberar la herramienta
            DB::table('herramienta')->where('id', $entrega->herramienta_id)->update([
                'estado' => 'disponible',
                'updated_at' => now(),
            ]);
        });

        return back()->with('alert', ['icon' => 'success', 'title' => 'Devuelta', 'text' => 'Herramienta retornada al inventario.']);
    }

    // --- Métodos similares para EPP ---
    public function storeEpp(Request $request)
    {
        $request->validate([
            'id_epp' => 'required|integer',
            'id_usuario' => 'required|integer',
            'fecha_entrega' => 'required|date',
            'estado_elemento' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            DB::table('entrega_epps')->insert([
                'epp_id'          => $request->id_epp,
                'user_id'         => $request->id_usuario,
                'fecha_entrega'   => $request->fecha_entrega,
                'estado_elemento' => $request->estado_elemento,
                'observaciones'   => $request->observaciones,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            // Descontar 1 del stock disponible
            DB::table('epp')->where('id', $request->id_epp)->decrement('stock_disponible', 1);
        });

        return back()->with('alert', ['icon' => 'success', 'title' => 'Entregado', 'text' => 'EPP asignado correctamente.']);
    }

    public function returnEpp($id)
    {
        DB::transaction(function () use ($id) {
            $entrega = DB::table('entrega_epps')->where('id', $id)->first();
            DB::table('entrega_epps')->where('id', $id)->update([
                'fecha_devolucion' => now(),
                'updated_at' => now(),
            ]);
            // Restaurar 1 al stock disponible
            DB::table('epp')->where('id', $entrega->epp_id)->increment('stock_disponible', 1);
        });

        return back()->with('alert', ['icon' => 'success', 'title' => 'Devuelto', 'text' => 'EPP retornado al inventario.']);
    }
}
