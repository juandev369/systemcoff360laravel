<?php

namespace App\Http\Controllers\Auth;

// Nota: reemplaza app/Http/Controllers/Auth/AuthenticatedSessionController.php generado por Breeze.

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra el formulario de login. Si el correo está bloqueado
     * por intentos fallidos, calcula hasta cuándo debe esperar.
     */
    public function create(Request $request): View
    {
        $loginEspera = 0;

        if ($email = old('email')) {
            $key = Str::transliterate(Str::lower($email) . '|' . $request->ip());

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $loginEspera = now()->addSeconds(RateLimiter::availableIn($key))->timestamp;
            }
        }

        return view('auth.login', [
            'loginEspera' => $loginEspera,
        ]);
    }

    /**
     * Procesa el intento de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
