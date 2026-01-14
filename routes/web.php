<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestokController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Pegawai (didaftarkan lebih dahulu agar tidak ketangkap route {transaksi})
    Route::get('/products-list', [ProdukController::class, 'listForPegawai'])->name('produk.list');
    Route::get('/transactions/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transactions', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/my-transactions', [TransaksiController::class, 'myTransactions'])->name('transaksi.my');

    // Rute untuk Admin
    Route::middleware('admin')->group(function () {
        // Kategori
        Route::resource('kategori', KategoriController::class);
        
        // Produk
        Route::resource('produk', ProdukController::class);
        
        // User Management
        Route::resource('users', UserController::class)->except(['show']);
        
        // Transactions
        Route::get('/transactions', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transactions/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::delete('/transactions/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
        
        // Laporan
        Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('laporan');
        Route::get('/export-laporan', [TransaksiController::class, 'export'])->name('export.laporan');
        
            // Restok (Fuzzy Logic)
            Route::get('/restok', [RestokController::class, 'index'])->name('restok.index');
            Route::post('/restok/proses', [RestokController::class, 'prosesRestok'])->name('restok.proses');
    });
});

require __DIR__.'/auth.php';