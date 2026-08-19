<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WorkerTaskController extends Controller
{
    // Mostrar las tareas del trabajador logueado
    public function index()
    {
        $idUsuario = Auth::id();

        // Consulta igual a la original, ordenando por estado y luego fecha límite
        $tareas = DB::table('asignacion_tarea as a')
            ->join('tarea as t', 'a.tarea_id', '=', 't.id')
            ->where('a.user_id', $idUsuario)
            ->select(
                'a.id as id_asignacion', 'a.fecha_asignacion',
                't.id as id_tarea', 't.descripcion', 't.prioridad', 't.estado as estado_general', 
                't.created_at as fecha_creacion', 't.fecha_limite'
            )
            ->orderByRaw("
                CASE 
                    WHEN t.estado = 'pendiente' THEN 1
                    WHEN t.estado = 'en_progreso' THEN 2
                    WHEN t.estado = 'completada' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('t.fecha_limite', 'asc')
            ->get();

        // Mapear estados en memoria para la lógica de la vista
        $tareas = $tareas->map(function ($t) {
            $t->estado_asignacion = match ($t->estado_general) {
                'pendiente' => 'pendiente',
                'en_progreso' => 'en proceso',
                'completada' => 'finalizada',
                default => $t->estado_general
            };
            return $t;
        });

        // Calcular totales
        $total = $tareas->count();
        $pendientes = $tareas->where('estado_asignacion', 'pendiente')->count();
        $proceso = $tareas->where('estado_asignacion', 'en proceso')->count();
        $finalizadas = $tareas->where('estado_asignacion', 'finalizada')->count();

        return view('trabajador.tareas.index', compact('tareas', 'total', 'pendientes', 'proceso', 'finalizadas'));
    }

    // Cambiar estado a "en proceso"
    public function startTask($id_tarea)
    {
        DB::table('tarea')
            ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
            ->where('tarea.id', $id_tarea)
            ->where('asignacion_tarea.user_id', Auth::id())
            ->update([
                'tarea.estado' => 'en_progreso',
                'tarea.updated_at' => now()
            ]);

        return back();
    }

    // Subir evidencia y finalizar tarea
    public function completeTask(Request $request, $id_tarea)
    {
        $request->validate([
            'comentario' => 'required|string',
            'evidencia'  => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Máx 5MB
        ]);

        $idUsuario = Auth::id();

        try {
            DB::transaction(function () use ($request, $id_tarea, $idUsuario) {
                // 1. Asegurar que la carpeta public/uploads/evidencias existe
                $destPath = public_path('uploads/evidencias');
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }

                // 2. Guardar el archivo en la carpeta public/uploads/evidencias
                $file = $request->file('evidencia');
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'evidencia_' . $idUsuario . '_' . time() . '.' . $extension;
                
                // Mueve el archivo físico
                $file->move($destPath, $nombreArchivo);

                // Determinar tipo enum de evidencia
                $tipo = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']) ? 'foto' : 'documento';

                // 3. Insertar en la tabla evidencia_tarea
                DB::table('evidencia_tarea')->insert([
                    'tarea_id'    => $id_tarea,
                    'user_id'     => $idUsuario,
                    'archivo'     => 'uploads/evidencias/' . $nombreArchivo,
                    'tipo'        => $tipo,
                    'descripcion' => $request->comentario,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                // 4. Marcar como finalizada (completada) en la tabla tarea
                DB::table('tarea')
                    ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
                    ->where('tarea.id', $id_tarea)
                    ->where('asignacion_tarea.user_id', $idUsuario)
                    ->update([
                        'tarea.estado' => 'completada',
                        'tarea.fecha_completada' => now(),
                        'tarea.updated_at' => now(),
                    ]);
            });

            return back()->with('alert', [
                'icon'  => 'success',
                'title' => '¡Excelente!',
                'text'  => 'Tarea completada correctamente.'
            ]);

        } catch (\Exception $e) {
            return back()->with('alert', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'Hubo un problema al subir la evidencia.'
            ]);
        }
    }

    public function history(Request $request)
    {
        $idUsuario = Auth::id();

        // Iniciamos la consulta base con los JOINs correspondientes
        $query = DB::table('asignacion_tarea as a')
            ->join('tarea as t', 'a.tarea_id', '=', 't.id')
            ->leftJoin('evidencia_tarea as e', function($join) use ($idUsuario) {
                $join->on('e.tarea_id', '=', 't.id')
                     ->where('e.user_id', '=', $idUsuario);
            })
            ->where('a.user_id', $idUsuario)
            ->select(
                'a.id as id_asignacion', 'a.fecha_asignacion',
                't.id as id_tarea', 't.descripcion', 't.prioridad', 't.estado as estado_general',
                't.created_at as fecha_creacion', 't.fecha_limite',
                'e.archivo', 'e.tipo as tipo_archivo', 'e.created_at as fecha_subida'
            );

        // Filtro por fecha "Desde"
        if ($request->filled('desde')) {
            $query->where('a.fecha_asignacion', '>=', $request->desde . ' 00:00:00');
        }

        // Filtro por fecha "Hasta"
        if ($request->filled('hasta')) {
            $query->where('a.fecha_asignacion', '<=', $request->hasta . ' 23:59:59');
        }

        // Ejecutamos la consulta ordenada por fecha
        $historial = $query->orderBy('a.fecha_asignacion', 'desc')->get();

        // Mapear estados en memoria para la lógica de la vista
        $historial = $historial->map(function ($item) {
            $item->estado_asignacion = match ($item->estado_general) {
                'pendiente' => 'pendiente',
                'en_progreso' => 'en proceso',
                'completada' => 'finalizada',
                default => $item->estado_general
            };
            return $item;
        });

        $totalHistorial = $historial->count();

        return view('trabajador.tareas.history', compact('historial', 'totalHistorial'));
    }
}
