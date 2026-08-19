<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\AdminTareaController;
use App\Http\Controllers\PagoNominaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AdminEntregaController;
use App\Http\Controllers\WorkerDashboardController;
use App\Http\Controllers\WorkerTaskController;
use App\Http\Controllers\WorkerNominaController;
use App\Http\Controllers\WorkerProfileController;
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
    if (auth()->user()->role_id == 2) {
        return redirect()->route('trabajador.dashboard');
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
    Route::resource('ventas', VentaController::class);
    
    // Rutas para Tareas
    Route::get('tareas', [AdminTareaController::class, 'index'])->name('tareas.index');
    Route::post('tareas', [AdminTareaController::class, 'store'])->name('tareas.store');
    Route::patch('tareas/{id}/status', [AdminTareaController::class, 'updateStatus'])->name('tareas.status');
    Route::delete('tareas/{id}', [AdminTareaController::class, 'destroy'])->name('tareas.destroy');
    
    Route::resource('nomina', PagoNominaController::class)->except(['create', 'show', 'edit', 'update']);

    // Rutas para Inventario
    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('inventario/insumo', [InventarioController::class, 'storeInsumo'])->name('inventario.insumo.store');
    Route::post('inventario/herramienta', [InventarioController::class, 'storeHerramienta'])->name('inventario.herramienta.store');
    Route::post('inventario/epp', [InventarioController::class, 'storeEpp'])->name('inventario.epp.store');

    // Rutas para Entregas
    Route::get('entregas', [AdminEntregaController::class, 'index'])->name('entregas.index');
    Route::post('entregas/herramienta', [AdminEntregaController::class, 'storeHerramienta'])->name('entregas.herramienta.store');
    Route::post('entregas/epp', [AdminEntregaController::class, 'storeEpp'])->name('entregas.epp.store');
    Route::patch('entregas/herramienta/{id}/devolver', [AdminEntregaController::class, 'returnHerramienta'])->name('entregas.herramienta.return');
});

// 4. GRUPO DEL TRABAJADOR
Route::middleware(['auth'])->prefix('trabajador')->group(function () {
    Route::get('/dashboard', [WorkerDashboardController::class, 'index'])->name('trabajador.dashboard');
    
    // Rutas de las Tareas
    Route::get('/tareas', [WorkerTaskController::class, 'index'])->name('trabajador.tareas');
    Route::get('/historial', [WorkerTaskController::class, 'history'])->name('trabajador.historial');
    Route::patch('/tareas/{id}/start', [WorkerTaskController::class, 'startTask'])->name('trabajador.tareas.start');
    Route::post('/tareas/{id}/complete', [WorkerTaskController::class, 'completeTask'])->name('trabajador.tareas.complete');

    // Ruta de Mi Nómina
    Route::get('/nomina', [WorkerNominaController::class, 'index'])->name('trabajador.nomina');

    // Rutas del perfil
    Route::get('/perfil', [WorkerProfileController::class, 'edit'])->name('trabajador.perfil');
    Route::patch('/perfil', [WorkerProfileController::class, 'update'])->name('trabajador.perfil.update');
    Route::put('/perfil/password', [WorkerProfileController::class, 'updatePassword'])->name('trabajador.password.update');
});

// 5. Rutas de perfil nativas de Laravel Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Carga las rutas de login y registro
require __DIR__.'/auth.php';