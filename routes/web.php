<?php

use Illuminate\Support\Facades\Route;

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

// Routes protégées par authentification seulement
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboards - la vérification des rôles se fait dans les contrôleurs
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');
    
    Route::get('/dashboard/operateur', [DashboardController::class, 'operateur'])
        ->name('dashboard.operateur');
        
    // Route générique dashboard qui redirige selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});