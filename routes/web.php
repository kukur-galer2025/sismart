<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Language Switch
Route::post('/set-lang/{lang}', function ($lang) {
    session(['sismart-lang' => in_array($lang, ['id', 'en']) ? $lang : 'id']);
    return response()->json(['ok' => true]);
})->name('set-lang');

// Auth Routes
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kategori', KategoriController::class)->except(['create', 'show', 'edit']);
    Route::resource('barang', BarangController::class);
    Route::resource('barang-masuk', BarangMasukController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('barang-keluar', BarangKeluarController::class)->only(['index', 'create', 'store', 'show']);

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('/perputaran', [LaporanController::class, 'perputaran'])->name('perputaran');
    });

    Route::get('/manual-book', function() {
        return view('manual-book');
    })->name('manual-book');

    Route::get('/tentang-kami', function() {
        return view('tentang-kami');
    })->name('tentang-kami');

    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/laba-rugi', [KeuanganController::class, 'labaRugi'])->name('laba-rugi');
        Route::get('/perubahan-modal', [KeuanganController::class, 'perubahanModal'])->name('perubahan-modal');
        Route::get('/neraca', [KeuanganController::class, 'neraca'])->name('neraca');
        Route::get('/pengeluaran', [\App\Http\Controllers\PengeluaranController::class, 'create'])->name('pengeluaran.create');
        Route::post('/pengeluaran', [\App\Http\Controllers\PengeluaranController::class, 'store'])->name('pengeluaran.store');
        Route::get('/jurnal', [KeuanganController::class, 'jurnal'])->name('jurnal');
    });

    // Export Routes
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/stok/excel', [ExportController::class, 'stokExcel'])->name('stok.excel');
        Route::get('/stok/pdf', [ExportController::class, 'stokPdf'])->name('stok.pdf');
        Route::get('/transaksi/excel', [ExportController::class, 'transaksiExcel'])->name('transaksi.excel');
        Route::get('/transaksi/pdf', [ExportController::class, 'transaksiPdf'])->name('transaksi.pdf');
        Route::get('/laba-rugi/pdf', [ExportController::class, 'labaRugiPdf'])->name('laba-rugi.pdf');
        Route::get('/neraca/pdf', [ExportController::class, 'neracaPdf'])->name('neraca.pdf');
        Route::get('/jurnal/pdf', [ExportController::class, 'jurnalPdf'])->name('jurnal.pdf');
        Route::get('/jurnal/excel', [ExportController::class, 'jurnalExcel'])->name('jurnal.excel');
        Route::get('/barang-masuk/pdf', [ExportController::class, 'barangMasukPdf'])->name('barang-masuk.pdf');
        Route::get('/barang-masuk/excel', [ExportController::class, 'barangMasukExcel'])->name('barang-masuk.excel');
        Route::get('/barang-keluar/pdf', [ExportController::class, 'barangKeluarPdf'])->name('barang-keluar.pdf');
        Route::get('/barang-keluar/excel', [ExportController::class, 'barangKeluarExcel'])->name('barang-keluar.excel');
        Route::get('/perputaran/pdf', [ExportController::class, 'perputaranPdf'])->name('perputaran.pdf');
        Route::get('/perputaran/excel', [ExportController::class, 'perputaranExcel'])->name('perputaran.excel');
        Route::get('/laba-rugi/excel', [ExportController::class, 'labaRugiExcel'])->name('laba-rugi.excel');
        Route::get('/neraca/excel', [ExportController::class, 'neracaExcel'])->name('neraca.excel');
    });

    Route::get('/api/barang/{barang}', function (App\Models\Barang $barang) {
        return response()->json($barang);
    })->name('api.barang.show');
});
