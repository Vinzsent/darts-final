<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\SupplyRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CanvassController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\MenuController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::post('/menu/settings/unlock', [MenuController::class, 'settingsUnlock'])->name('menu.settings.unlock');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::get('/inventory/{id}/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::post('/inventory/{id}/stock-adjust', [InventoryController::class, 'stockAdjust'])->name('inventory.stock-adjust');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Procurement
    Route::resource('procurement', ProcurementController::class);
    Route::post('/procurement/{id}/receive', [ProcurementController::class, 'markReceived'])->name('procurement.receive');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');

    // Supply Requests
    Route::resource('supply-requests', SupplyRequestController::class);
    Route::post('supply-requests/{id}/approve', [SupplyRequestController::class, 'approve'])->name('supply-requests.approve');
    Route::post('supply-requests/{id}/reject', [SupplyRequestController::class, 'reject'])->name('supply-requests.reject');

    // Users (admin only)
    Route::resource('users', UsersController::class)->middleware('admin');

    // Canvass
    Route::resource('canvass', CanvassController::class);

    // Purchase Orders
    Route::resource('purchase-orders', PurchaseOrderController::class);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Property
    Route::resource('property', PropertyController::class);
});
