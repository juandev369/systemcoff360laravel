<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class WorkerProfileController extends Controller
{
    // Mostrar la vista del perfil
    public function edit()
    {
        $usuario = Auth::user();
        return view('trabajador.perfil.edit', compact('usuario'));
    }

    // Actualizar datos básicos (Nombre, DNI, Teléfono, Correo)
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombres'   => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dni'       => ['required', 'string', 'max:20', 'unique:users,dni,' . $usuario->id],
            'email'     => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $usuario->id],
            'telefono'  => ['nullable', 'string', 'max:30'],
        ]);

        $usuario->update([
            'name'     => $request->nombres . ' ' . $request->apellidos,
            'dni'      => $request->dni,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        return back()->with('alert', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'text'  => 'Tu información personal se ha guardado correctamente.'
        ]);
    }

    // Actualizar contraseña
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('alert', [
            'icon'  => 'success',
            'title' => 'Contraseña cambiada',
            'text'  => 'Tu contraseña se ha actualizado correctamente.'
        ]);
    }
}
