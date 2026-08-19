<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PagoNomina;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WorkerNominaController extends Controller
{
    public function index()
    {
        $idUsuario = Auth::id(); // Obtenemos el ID del trabajador logueado

        // ── Métricas personales ──
        $totalRecibido = PagoNomina::where('user_id', $idUsuario)->sum('monto');
        
        $recibidoMes = PagoNomina::where('user_id', $idUsuario)
                                 ->whereMonth('fecha', now()->month)
                                 ->whereYear('fecha', now()->year)
                                 ->sum('monto');
                                 
        $totalPagos = PagoNomina::where('user_id', $idUsuario)->count();
        
        $ultimoPago = PagoNomina::where('user_id', $idUsuario)->max('fecha');

        // ── Historial de pagos del trabajador ──
        // Usamos "with('admin')" para cargar el nombre de quien registró el pago (relación definida previamente)
        $pagos = PagoNomina::with('admin')
                           ->where('user_id', $idUsuario)
                           ->orderBy('fecha', 'desc')
                           ->orderBy('id', 'desc')
                           ->get();

        // ── Pagos agrupados por mes (para resumen) ──
        // Agrupamos en memoria para ser 100% compatibles con SQLite en testing y MySQL en prod
        $porMes = $pagos->groupBy(function ($pago) {
            return date('Y-m', strtotime($pago->fecha));
        })->map(function ($group, $key) {
            $fecha = Carbon::parse($group->first()->fecha);
            $mesLabel = $fecha->translatedFormat('F Y');

            return (object)[
                'mes' => $key,
                'mes_label' => $mesLabel,
                'total_mes' => $group->sum('monto'),
                'cantidad' => $group->count()
            ];
        })->take(6)->values();

        return view('trabajador.nomina.index', compact(
            'totalRecibido', 'recibidoMes', 'totalPagos', 'ultimoPago', 'pagos', 'porMes'
        ));
    }
}
