<?php

namespace App\Http\Controllers\Auth;

// Nota: reemplaza app/Http/Controllers/Auth/RegisteredUserController.php generado por Breeze.

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra el formulario de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:150', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ], [
            'terms.required' => 'Debes aceptar los términos de servicio.',
            'terms.accepted' => 'Debes aceptar los términos de servicio.',
        ]);

        // Todo usuario que se registra por su cuenta entra como "Trabajador".
        $rolTrabajador = Role::where('nombre', 'Trabajador')->first();

        $user = User::create([
            'name' => trim($request->nombres . ' ' . $request->apellidos),
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $rolTrabajador?->id,
            'estado' => 'activo',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
