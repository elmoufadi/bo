<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 📁 routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Page d'accueil - redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboards selon les rôles
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin')
        ->middleware('check.role:admin');
    
    Route::get('/dashboard/operateur', [DashboardController::class, 'operateur'])
        ->name('dashboard.operateur')
        ->middleware('check.role:operateur');
});