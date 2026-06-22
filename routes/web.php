<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Kasir\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LandingSettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;

// ── Public Landing Page ──────────────────────────
Route::get('/', fn() => view('landing'))->name('landing');

// ── Auth ──────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/contact-admin', [AuthController::class, 'showContact'])->name('contact.admin');
Route::post('/contact-admin', [AuthController::class, 'sendContact'])->name('contact.send');

// ── Admin ─────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/barcode-lookup', [ProductController::class, 'lookupByBarcode'])->name('products.barcode-lookup');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/landing-settings', [LandingSettingController::class, 'index'])->name('landing-settings.index');
    Route::post('/landing-settings', [LandingSettingController::class, 'update'])->name('landing-settings.update');
});

// ── Kasir ─────────────────────────────────────────
Route::prefix('kasir')->name('kasir.')->middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transaksi', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{id}/struk', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transaksi/history', [TransactionController::class, 'history'])->name('transactions.history');
});
