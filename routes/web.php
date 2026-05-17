<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\User\CatalogController;
use App\Http\Controllers\User\FakturController;

// Root redirect
Route::get('/', function () {
    if (!auth()->check())
        return redirect()->route('login');
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.catalog');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{barang}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{barang}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// User
Route::prefix('user')->name('user.')->middleware(['auth', 'useronly'])->group(function () {
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
    Route::post('/catalog/{barang}/add-cart', [CatalogController::class, 'addToCart'])->name('catalog.addToCart');
    Route::put('/cart/{barangId}', [CatalogController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{barangId}', [CatalogController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/faktur', [FakturController::class, 'index'])->name('faktur.index');
    Route::get('/faktur/create', [FakturController::class, 'create'])->name('faktur.create');
    Route::post('/faktur', [FakturController::class, 'store'])->name('faktur.store');
    Route::get('/faktur/{faktur}', [FakturController::class, 'show'])->name('faktur.show');
    Route::get('/riwayat-faktur', [FakturController::class, 'history'])->name('faktur.history');
});