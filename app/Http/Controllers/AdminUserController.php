<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Muestra la lista de usuarios con los filtros aplicados
    public function index(Request $request)
    {
        // 1. Iniciar la consulta base
        $query = User::query();

        // 2. Aplicar filtro de búsqueda (nombre, dni o email)
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function($q) use ($busqueda) {
                $q->where('name', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%")
                  ->orWhere('dni', 'like', "%{$busqueda}%");
            });
        }

        // 3. Aplicar filtro de estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 4. Aplicar filtro de rol (asumiendo 1 = Admin, 2 = Trabajador)
        if ($request->filled('rol')) {
            $roleId = $request->rol === 'administrador' ? 1 : 2;
            $query->where('role_id', $roleId);
        }

        // Obtener los usuarios filtrados
        $usuarios = $query->orderBy('created_at', 'desc')->get();

        // Métricas globales (sin filtros)
        $totalUsuarios = User::count();
        $totalAdmins = User::where('role_id', 1)->count();
        $totalTrabajadores = User::where('role_id', 2)->count();
        $totalActivos = User::where('estado', 'activo')->count();
        $totalInactivos = User::where('estado', '!=', 'activo')->count();

        return view('admin.usuarios.index', compact(
            'usuarios', 'totalUsuarios', 'totalAdmins', 
            'totalTrabajadores', 'totalActivos', 'totalInactivos'
        ));
    }

    // Muestra el formulario
    public function create()
    {
        return view('admin.usuarios.create');
    }

    // Procesa y guarda los datos
    public function store(Request $request)
    {
        // 1. Validar los datos que llegan del formulario
        $request->validate([
            'nombres'   => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni'       => ['required', 'string', 'max:20', 'unique:users,dni'],
            'email'     => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'telefono'  => ['nullable', 'string', 'max:30'],
            // La regla 'confirmed' busca automáticamente un campo llamado 'password_confirmation'
            'password'  => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // 2. Crear el usuario en la base de datos
        User::create([
            'name'     => $request->nombres . ' ' . $request->apellidos,
            'dni'      => $request->dni,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'role_id'  => 2, // 2 equivale a Trabajador
            'estado'   => 'activo',
        ]);

        // 3. Redirigir al dashboard con la alerta de éxito
        return redirect()->route('admin.dashboard')->with('alert', [
            'icon'  => 'success',
            'title' => '¡Trabajador registrado!',
            'text'  => 'La cuenta se ha creado exitosamente.'
        ]);
    }

    // Cambiar estado (Activar/Desactivar)
    public function toggleStatus(Request $request, User $usuario)
    {
        // Prevenir que el usuario se desactive a sí mismo
        if (auth()->id() === $usuario->id) {
            return back()->with('alert', [
                'icon' => 'error',
                'title' => 'Acción denegada',
                'text' => 'No puedes cambiar tu propio estado.'
            ]);
        }

        $usuario->estado = $request->estado;
        $usuario->save();

        return back()->with('alert', [
            'icon' => 'success',
            'title' => 'Estado actualizado',
            'text' => 'El estado del usuario se actualizó correctamente.'
        ]);
    }

    // Eliminar usuario
    public function destroy(User $usuario)
    {
        // Prevenir que el usuario se elimine a sí mismo
        if (auth()->id() === $usuario->id) {
            return back()->with('alert', [
                'icon' => 'error',
                'title' => 'Acción denegada',
                'text' => 'No puedes eliminar tu propia cuenta.'
            ]);
        }

        $usuario->delete();

        return back()->with('alert', [
            'icon' => 'success',
            'title' => 'Usuario eliminado',
            'text' => 'El usuario ha sido eliminado del sistema.'
        ]);
    }
}
