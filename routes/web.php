<?php

use App\Http\Controllers\Hosting\PanelController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::get('hosting', [PanelController::class, 'index'])->name('hosting.index');
    Route::post('hosting/users', [PanelController::class, 'storeUser'])->name('hosting.users.store');
    Route::patch('hosting/users/{user}', [PanelController::class, 'updateUser'])->name('hosting.users.update');
    Route::delete('hosting/users/{user}', [PanelController::class, 'destroyUser'])->name('hosting.users.destroy');

    Route::post('hosting/servers', [PanelController::class, 'storeManagedServer'])->name('hosting.servers.store');
    Route::patch('hosting/servers/{managedServer}', [PanelController::class, 'updateManagedServer'])->name('hosting.servers.update');
    Route::delete('hosting/servers/{managedServer}', [PanelController::class, 'destroyManagedServer'])->name('hosting.servers.destroy');
    Route::get('hosting/servers/{managedServer}/export', [PanelController::class, 'exportManagedServerConfig'])->name('hosting.servers.export');

    Route::post('hosting/domains', [PanelController::class, 'storeDomain'])->name('hosting.domains.store');
    Route::patch('hosting/domains/{domain}', [PanelController::class, 'updateDomain'])->name('hosting.domains.update');
    Route::delete('hosting/domains/{domain}', [PanelController::class, 'destroyDomain'])->name('hosting.domains.destroy');

    Route::post('hosting/subdomains', [PanelController::class, 'storeSubdomain'])->name('hosting.subdomains.store');
    Route::patch('hosting/subdomains/{subdomain}', [PanelController::class, 'updateSubdomain'])->name('hosting.subdomains.update');
    Route::delete('hosting/subdomains/{subdomain}', [PanelController::class, 'destroySubdomain'])->name('hosting.subdomains.destroy');
});

require __DIR__.'/settings.php';
