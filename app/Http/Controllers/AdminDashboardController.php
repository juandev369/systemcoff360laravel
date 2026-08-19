<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Métricas de usuarios (usando el modelo User de Laravel)
        $totalUsuarios = User::count();
        $totalAdmins = User::where('role_id', 1)->count();
        $totalTrabajadores = User::where('role_id', 2)->count();
        $totalActivos = User::where('estado', 'activo')->count();
        $totalInactivos = User::where('estado', '!=', 'activo')->count();

        // Métricas globales (usando DB::table para las tablas antiguas que aún no tienen modelo)
        $totalLotes = $this->valorSeguro('lotes'); // Usamos 'lotes' porque ya la migramos
        $totalTareas = $this->valorSeguro('tarea');
        $totalInsumos = $this->valorSeguro('insumo');
        $totalHerramientas = $this->valorSeguro('herramienta');
        $totalActivosFinca = $this->valorSeguro('activo');
        $totalProveedores = $this->valorSeguro('proveedor');
        
        $totalCompras = $this->valorSeguro('compra');
        $totalVentas = $this->valorSeguro('ventas');
        $totalCosechas = $this->valorSeguro('cosecha');
        $totalMantenimientos = $this->valorSeguro('mantenimiento');

        // Consultas recientes
        $usuariosRecientes = User::with('role')->orderBy('created_at', 'desc')->limit(6)->get();
        $tareasRecientes = $this->filasSeguras('tarea', 5);
        $cosechasRecientes = $this->filasSeguras('cosecha', 5);
        
        // Notificaciones / Alertas activas (simuladas por ahora para no romper la vista)
        $alertasActivas = [];
        $totalAlertas = 0;

        return view('admin.dashboard', compact(
            'totalUsuarios', 'totalAdmins', 'totalTrabajadores', 'totalActivos', 'totalInactivos',
            'totalLotes', 'totalTareas', 'totalInsumos', 'totalHerramientas', 'totalActivosFinca', 'totalProveedores',
            'totalCompras', 'totalVentas', 'totalCosechas', 'totalMantenimientos',
            'usuariosRecientes', 'tareasRecientes', 'cosechasRecientes',
            'alertasActivas', 'totalAlertas'
        ));
    }

    // Funciones de ayuda seguras
    private function valorSeguro($tabla)
    {
        try {
            return DB::table($tabla)->count();
        } catch (\Exception $e) {
            return 0; // Si la tabla no existe aún, devuelve 0 sin romper el sistema
        }
    }

    private function filasSeguras($tabla, $limite)
    {
        try {
            return DB::table($tabla)->orderBy('id', 'desc')->limit($limite)->get();
        } catch (\Exception $e) {
            return collect([]); // Devuelve una colección vacía
        }
    }
}