<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkerDashboardController extends Controller
{
    public function index()
    {
        $idUsuario = Auth::id(); // Obtiene el ID del trabajador logueado

        // Métricas de tareas del trabajador
        $totalTareas = DB::table('tarea')
            ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
            ->where('asignacion_tarea.user_id', $idUsuario)
            ->count();

        $tareasPendientes = DB::table('tarea')
            ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
            ->where('asignacion_tarea.user_id', $idUsuario)
            ->where('tarea.estado', 'pendiente')
            ->count();

        $tareasProceso = DB::table('tarea')
            ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
            ->where('asignacion_tarea.user_id', $idUsuario)
            ->where('tarea.estado', 'en_progreso')
            ->count();

        $tareasFinalizadas = DB::table('tarea')
            ->join('asignacion_tarea', 'tarea.id', '=', 'asignacion_tarea.tarea_id')
            ->where('asignacion_tarea.user_id', $idUsuario)
            ->where('tarea.estado', 'completada')
            ->count();

        // Notificaciones: Tareas nuevas
        $tareasNuevas = DB::table('asignacion_tarea')
            ->join('tarea', 'asignacion_tarea.tarea_id', '=', 'tarea.id')
            ->where('asignacion_tarea.user_id', $idUsuario)
            ->where('tarea.estado', 'pendiente')
            ->orderBy('asignacion_tarea.fecha_asignacion', 'desc')
            ->select('tarea.descripcion', 'tarea.prioridad')
            ->limit(5)
            ->get();

        // Notificaciones: Pagos recientes
        $pagosRecientes = DB::table('pago_nominas')
            ->where('user_id', $idUsuario)
            ->orderBy('fecha', 'desc')
            ->limit(3)
            ->get();

        // Construir el array de notificaciones unificado
        $notifTrabajador = [];
        foreach ($tareasNuevas as $t) {
            $notifTrabajador[] = [
                'tipo'   => 'tarea',
                'ico'    => 'fa-clipboard-list',
                'titulo' => 'Nueva tarea asignada',
                'msg'    => Str::limit($t->descripcion, 50) . ' [' . $t->prioridad . ']',
                'link'   => '#' // Aquí irá la ruta de sus tareas
            ];
        }
        foreach ($pagosRecientes as $p) {
            $notifTrabajador[] = [
                'tipo'   => 'pago',
                'ico'    => 'fa-credit-card',
                'titulo' => 'Pago recibido',
                'msg'    => '$' . number_format($p->monto, 0, ',', '.') . ' — ' . ucfirst($p->tipo_pago) . ' · ' . $p->fecha,
                'link'   => '#' // Aquí irá la ruta de su nómina
            ];
        }

        $totalNotif = count($notifTrabajador);

        return view('trabajador.dashboard', compact(
            'totalTareas', 'tareasPendientes', 'tareasProceso', 'tareasFinalizadas',
            'notifTrabajador', 'totalNotif'
        ));
    }
}
