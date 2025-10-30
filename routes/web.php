<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// No need to import controllers when using string notation

// ==========================================================
// 1. ROUTE PUBLIC (LANDING PAGE & REQUESTS)
// ==========================================================

// Landing Page (Halaman Utama)
Route::get('/', 'RequestController@landingPage')->name('landing-page');

// Request Barang (Form Publik)
Route::get('/request-barang', 'RequestController@createBarang')->name('request.barang.create');
Route::post('/request-barang', 'RequestController@storeBarang')->name('request.barang.store');

// Request Link Zoom (Form Publik)
Route::get('/request-link-zoom', 'RequestController@createZoom')->name('request.zoom.create');
Route::post('/request-link-zoom', 'RequestController@storeZoom')->name('request.zoom.store');

# Route Otentikasi Laravel (Login, Register, Logout)
Auth::routes();


// ==========================================================
// 2. ROUTE DASHBOARD ADMIN (SUPER ADMIN & ADMIN BARANG)
// ==========================================================

// Dashboard route accessible by both super_admin and admin_barang
Route::get('dashboard', 'AdminDashboardController@index')
    ->middleware(['auth', 'role:super_admin,admin_barang'])
    ->name('dashboard.index');

// Other admin routes
Route::middleware(['auth', 'role:super_admin,admin_barang'])
    ->prefix('dashboard')
    ->group(function () {

    // Manajemen Barang
    Route::resource('barang', 'ItemController');
    // Tambah Stok (aksi dari halaman daftar barang)
    Route::post('barang/add-stock', 'ItemController@addStock')->name('barang.addStock');

    // Approval Section
    Route::prefix('approvals')->group(function () {

        // Approval Barang
        Route::get('barang', 'RequestController@index')->name('requests.index');
        Route::post('barang/{reqBarang}/reject', 'RequestController@reject')->name('requests.reject');
        Route::post('barang/{reqBarang}/approve', 'RequestController@approve')->name('requests.approve');

        // Approval Zoom
        Route::get('zoom', 'ZoomRequestController@index')->name('zoom.requests.index');
        Route::post('/zoom/requests/{reqZoom}/reject', 'ZoomRequestController@reject')->name('zoom.requests.reject');
        Route::post('zoom/{reqZoom}/approve', 'ZoomRequestController@approve')->name('zoom.requests.approve');
    });

    // Pengaturan (Settings)
    Route::prefix('settings')->group(function () {
        Route::get('template', 'SettingController@templateIndex')->name('template.index');
        Route::post('template/update', 'SettingController@updateTemplate')->name('template.update');
        Route::get('response', 'SettingController@responseIndex')->name('response.index');
    });

    // Riwayat Transaksi
    Route::get('transaksi', 'TransactionController@index')->name('transaksi.index');
});


// ==========================================================
// 3. ROUTE KHUSUS SUPER ADMIN
// ==========================================================

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super')
    ->name('super.')
    ->group(function () {

    // Manajemen User
    Route::resource('users', 'UserController');
});


// ==========================================================
// 4. ROUTE FALLBACK (Redirect ke Home)
// ==========================================================

Route::get('/home', function () {
    // Jika login dan role cocok, arahkan ke dashboard
    if (Auth::check()) {
        $role = Auth::user()->role;
        if (in_array($role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }
    }

    // Kalau belum login atau bukan admin, tampilkan landing page biasa
    return redirect()->route('landing-page');
})->name('home');



// EXPORT EXCEL
Route::get('/export/barang', 'ExportController@exportBarang')->name('export.barang');
Route::get('/export/transaksi', 'ExportController@exportTransaksi')->name('export.transactions');
