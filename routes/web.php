<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\PieceJointeController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\UserController;

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
    
    // Dashboards
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/operateur', [DashboardController::class, 'operateur'])->name('dashboard.operateur');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // === ROUTES MESSAGES ===
    Route::prefix('messages')->name('messages.')->group(function () {
        // Routes principales (CRUD)
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('/{message}', [MessageController::class, 'show'])->name('show');
        Route::get('/{message}/edit', [MessageController::class, 'edit'])->name('edit');
        Route::put('/{message}', [MessageController::class, 'update'])->name('update');
        Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
        
        // Routes spéciales
        Route::post('/{message}/distribute', [MessageController::class, 'distribute'])->name('distribute');
        Route::post('/{message}/mark-processed', [MessageController::class, 'markAsProcessed'])->name('mark-processed');
        Route::get('/urgent/list', [MessageController::class, 'urgent'])->name('urgent');
        Route::get('/non-distribues/list', [MessageController::class, 'nonDistribues'])->name('non-distribues');
        Route::get('/search/results', [MessageController::class, 'search'])->name('search');
    });
    
    // === ROUTES SERVICES (Admin seulement) ===
    Route::middleware('check.role:admin')->prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/', [ServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
        
        // Actions spéciales
        Route::post('/{service}/activate', [ServiceController::class, 'activate'])->name('activate');
        Route::post('/{service}/deactivate', [ServiceController::class, 'deactivate'])->name('deactivate');
        Route::post('/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{service}/messages', [ServiceController::class, 'messages'])->name('messages');
        Route::get('/{service}/statistiques', [ServiceController::class, 'statistiques'])->name('statistiques');
        Route::get('/export/csv', [ServiceController::class, 'export'])->name('export');
    });
    
    // === ROUTES DISTRIBUTIONS ===
    Route::prefix('distributions')->name('distributions.')->group(function () {
        Route::get('/', [DistributionController::class, 'index'])->name('index');
        Route::get('/service/{service}', [DistributionController::class, 'parService'])->name('par-service');
        Route::get('/{distribution}', [DistributionController::class, 'show'])->name('show');
        Route::delete('/{distribution}', [DistributionController::class, 'destroy'])->name('destroy');
        
        // Actions de lecture
        Route::post('/{distribution}/mark-read', [DistributionController::class, 'marquerCommeLu'])->name('mark-read');
        Route::post('/{distribution}/mark-unread', [DistributionController::class, 'marquerCommeNonLu'])->name('mark-unread');
        Route::post('/service/{service}/mark-all-read', [DistributionController::class, 'marquerToutCommeLu'])->name('mark-all-read');
        
        // Vues spéciales
        Route::get('/non-lus/list', [DistributionController::class, 'messagesNonLus'])->name('non-lus');
        Route::get('/urgents/list', [DistributionController::class, 'messagesUrgents'])->name('urgents');
        Route::get('/statistiques/all', [DistributionController::class, 'statistiques'])->name('statistiques');
        
        // Distribution et export
        Route::post('/message/{message}/distribute', [DistributionController::class, 'distribuer'])->name('distribuer');
        Route::get('/export/csv', [DistributionController::class, 'export'])->name('export');
    });
    
    // === ROUTES PIÈCES JOINTES ===
    Route::prefix('pieces-jointes')->name('pieces-jointes.')->group(function () {
        Route::get('/', [PieceJointeController::class, 'index'])->name('index');
        Route::get('/create', [PieceJointeController::class, 'create'])->name('create');
        Route::post('/', [PieceJointeController::class, 'store'])->name('store');
        Route::get('/{pieceJointe}', [PieceJointeController::class, 'show'])->name('show');
        Route::delete('/{pieceJointe}', [PieceJointeController::class, 'destroy'])->name('destroy');
        
        // Actions spéciales
        Route::get('/{pieceJointe}/download', [PieceJointeController::class, 'download'])->name('download');
        Route::get('/{pieceJointe}/preview', [PieceJointeController::class, 'preview'])->name('preview');
        Route::get('/{pieceJointe}/info', [PieceJointeController::class, 'getInfo'])->name('info');
        
        // AJAX
        Route::post('/upload-ajax', [PieceJointeController::class, 'uploadAjax'])->name('upload-ajax');
        Route::delete('/{pieceJointe}/destroy-ajax', [PieceJointeController::class, 'destroyAjax'])->name('destroy-ajax');
        
        // Stats et maintenance
        Route::get('/statistiques/all', [PieceJointeController::class, 'statistiques'])->name('statistiques');
        Route::post('/nettoyage', [PieceJointeController::class, 'nettoyage'])->name('nettoyage');
    });
    
    // === ROUTES STATISTIQUES ===
    Route::prefix('statistiques')->name('statistiques.')->group(function () {
        Route::get('/', [StatistiqueController::class, 'dashboard'])->name('dashboard');
        Route::get('/messages', [StatistiqueController::class, 'messages'])->name('messages');
        Route::get('/services', [StatistiqueController::class, 'services'])->name('services');
        Route::get('/utilisateurs', [StatistiqueController::class, 'utilisateurs'])->name('utilisateurs');
        Route::get('/pieces-jointes', [StatistiqueController::class, 'piecesJointes'])->name('pieces-jointes');
        Route::get('/rapport', [StatistiqueController::class, 'rapport'])->name('rapport');
        
        // API pour graphiques temps réel
        Route::get('/api/dashboard', [StatistiqueController::class, 'apiDashboard'])->name('api-dashboard');
    });

    // === ROUTES UTILISATEURS (Admin seulement) ===
    Route::middleware('check.role:admin')->prefix('users')->name('users.')->group(function () {
        Route::resource('/', UserController::class)->except(['show']);
    });

    // === ROUTES PARAMÈTRES SYSTÈME (Admin seulement) ===
    Route::middleware('check.role:admin')->prefix('system-settings')->name('system.settings.')->group(function () {
        Route::get('/', function () {
            return view('system-settings.index');
        })->name('index');
    });
});