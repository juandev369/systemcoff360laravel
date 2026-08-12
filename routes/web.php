<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

// 1. Página principal (Hero, slider, módulos)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Dashboard por defecto al iniciar sesión (redirige a admin.dashboard si es Administrador)
Route::get('/dashboard', function () {
    if (auth()->user()->role_id == 1 || auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. NUESTRAS RUTAS DE ADMINISTRADOR (NUEVO)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Ruta personalizada para cambiar el estado (debe ir ANTES del resource)
    Route::patch('usuarios/{usuario}/status', [AdminUserController::class, 'toggleStatus'])->name('usuarios.status');

    // Genera automáticamente las rutas index, create, store, show, edit, update, destroy
    Route::resource('lotes', LoteController::class);
    Route::resource('usuarios', AdminUserController::class);
    
});

// 4. Rutas de perfil nativas de Laravel Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Carga las rutas de login y registro
require __DIR__.'/auth.php';