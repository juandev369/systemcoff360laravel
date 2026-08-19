<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminTareaController extends Controller
{
    // Muestra el listado y el formulario
    public function index()
    {
        // Traemos a los trabajadores (asumiendo que su role_id es 2)
        $trabajadores = User::where('role_id', 2)->orderBy('name', 'asc')->get();

        // Consulta con JOINS respetando la lógica original pero adaptada al esquema de BD
        $tareas = DB::table('tarea as t')
            ->leftJoin('asignacion_tarea as at', 'at.tarea_id', '=', 't.id')
            ->leftJoin('users as u', 'u.id', '=', 'at.user_id') 
            ->leftJoin('evidencia_tarea as e', 'e.tarea_id', '=', 't.id')
            ->select(
                't.id as id_tarea', 't.descripcion', 't.prioridad', 't.estado', 't.created_at as fecha_creacion', 't.fecha_limite',
                'u.name as nombre', 'u.email',
                'e.archivo', 'e.descripcion as descripcion_evidencia', 'e.created_at as fecha_registro'
            )
            ->orderBy('t.id', 'desc')
            ->get();

        // Mapear los nombres de estados en BD a la vista del usuario
        $tareas = $tareas->map(function ($tarea) {
            if ($tarea->estado === 'en_progreso') {
                $tarea->estado = 'en proceso';
            }
            return $tarea;
        });

        return view('admin.tareas.index', compact('trabajadores', 'tareas'));
    }

    // Crea la tarea y la asigna en una sola transacción
    public function store(Request $request)
    {
        $request->validate([
            'descripcion'  => 'required|string',
            'id_usuario'   => 'required|integer',
            'prioridad'    => 'required|in:baja,media,alta',
            'fecha_limite' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Insertamos la tarea y obtenemos su ID generado
                $id_tarea = DB::table('tarea')->insertGetId([
                    'nombre'         => substr($request->descripcion, 0, 95) ?: 'Nueva Tarea',
                    'descripcion'    => $request->descripcion,
                    'prioridad'      => $request->prioridad,
                    'estado'         => 'pendiente',
                    'creada_por'     => auth()->id() ?? 1,
                    'fecha_inicio'   => now(),
                    'fecha_limite'   => $request->fecha_limite,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // 2. Asignamos la tarea al trabajador
                DB::table('asignacion_tarea')->insert([
                    'tarea_id'         => $id_tarea,
                    'user_id'          => $request->id_usuario,
                    'fecha_asignacion' => now(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            });

            return redirect()->route('tareas.index')->with('alert', [
                'icon'  => 'success',
                'title' => 'Tarea asignada',
                'text'  => 'La tarea fue creada y asignada al trabajador.'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tareas.index')->with('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se pudo crear la tarea: ' . $e->getMessage()
            ]);
        }
    }

    // Actualiza el estado de la tarea
    public function updateStatus(Request $request, $id)
    {
        $estado = $request->estado;
        if ($estado === 'en proceso') {
            $estado = 'en_progreso';
        } elseif ($estado === 'finalizada') {
            $estado = 'completada';
        }

        DB::table('tarea')->where('id', $id)->update([
            'estado' => $estado,
            'fecha_completada' => ($estado === 'completada') ? now() : null,
            'updated_at' => now(),
        ]);

        return redirect()->route('tareas.index')->with('alert', [
            'icon'  => 'success',
            'title' => 'Estado actualizado',
            'text'  => 'El estado de la tarea fue actualizado.'
        ]);
    }

    // Elimina la tarea y sus dependencias (evidencias y asignaciones)
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::table('evidencia_tarea')->where('tarea_id', $id)->delete();
                DB::table('asignacion_tarea')->where('tarea_id', $id)->delete();
                DB::table('tarea')->where('id', $id)->delete();
            });

            return redirect()->route('tareas.index')->with('alert', [
                'icon'  => 'success',
                'title' => 'Tarea eliminada',
                'text'  => 'La tarea fue eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tareas.index')->with('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Hubo un problema al eliminar la tarea.'
            ]);
        }
    }
}
