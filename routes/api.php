<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // POS routes (accessible by all authenticated users)
    Route::prefix('pos')->group(function () {
        Route::get('/products/search', [POSController::class, 'searchProducts']);
        Route::get('/products/barcode', [POSController::class, 'getProductByBarcode']);
        Route::post('/transaction', [POSController::class, 'processTransaction']);
        Route::get('/transaction/{id}/receipt', [POSController::class, 'getTransactionReceipt']);
        Route::get('/transactions/today', [POSController::class, 'getTodayTransactions']);
    });

    // Dashboard stats (accessible by all authenticated users)
    Route::get('/dashboard/stats', [ReportController::class, 'dashboardStats']);

    // Admin and SuperAdmin routes
    Route::middleware('role:admin,superadmin')->group(function () {
        
        // Product management
        Route::apiResource('products', ProductController::class);
        Route::post('/products/{id}/adjust-stock', [ProductController::class, 'adjustStock']);
        Route::get('/products/low-stock/list', [ProductController::class, 'getLowStockProducts']);

        // Category management
        Route::apiResource('categories', CategoryController::class);

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/sales', [ReportController::class, 'salesReport']);
            Route::get('/inventory', [ReportController::class, 'inventoryReport']);
            Route::get('/cashier', [ReportController::class, 'cashierReport']);
        });
    });

    // SuperAdmin only routes
    Route::middleware('role:superadmin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
    });
});