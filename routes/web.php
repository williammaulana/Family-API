<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    
    // Accessible to super_admin and admin
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('sales', SaleController::class);
    });

    // Only super_admin can manage users
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Cashier can only access POS
    Route::middleware(['role:cashier'])->group(function () {
        Route::get('/pos', [SaleController::class, 'create'])->name('pos.create');
        Route::post('/pos', [SaleController::class, 'store'])->name('pos.store');
    });
});

require __DIR__.'/auth.php';
