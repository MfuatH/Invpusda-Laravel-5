<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==================================================================================
// 1. ROUTE PUBLIC (BISA DIAKSES SIAPA SAJA: TAMU & ADMIN)
// ==================================================================================

Route::get('/', 'RequestController@landingPage')->name('landing-page');

// --- Request Barang
Route::get('/request-barang', 'RequestController@createBarang')->name('request.barang.create');
Route::post('/request-barang', 'RequestController@storeBarang')->name('request.barang.store');

// --- Request Zoom
Route::get('/request-link-zoom', 'RequestController@createZoom')->name('request.zoom.create');
Route::post('/request-link-zoom', 'RequestController@storeZoom')->name('request.zoom.store');

// --- Request Catering
Route::get('/request-konsumsi', 'RequestController@createKonsumsi')->name('request.konsumsi.create');
Route::post('/request-konsumsi', 'CateringController@store')->name('catering.store');
Route::get('/catering/success', 'CateringController@successPage')->name('catering.success');
Route::get('/template-doc', 'CateringController@templateDoc')->name('documents.template_doc');

// Route Hapus Catering (Publik - Agar tamu bisa hapus data sendiri jika salah input)
Route::delete('/request-konsumsi/{id}', 'CateringController@destroy')->name('catering.destroy');

// --- Request Kendaraan (FITUR BARU)
Route::get('/request-kendaraan', 'PeminjamanKendaraanController@create')->name('request.kendaraan.create');
Route::post('/request-kendaraan', 'PeminjamanKendaraanController@store')->name('request.kendaraan.store');

// --- Route Dokumen Lain
// Friendly fallback when no ID provided: redirect back with message
Route::get('/dashboard-doc', function() {
    return redirect()->route('landing-page')->with('error', 'ID dokumen tidak diberikan. Silakan pilih dokumen yang ingin dilihat.');
});

// Route with model binding: accepts `{catering}` and injects the Catering model
Route::get('/dashboard-doc/{catering}', 'RequestController@dashboardDoc')->name('documents.dashboard_doc');
Route::get('/undangan-upload', 'RequestController@createUndangan')->name('request.undangan.create');
Route::get('/download-presensi', 'RequestController@downloadPresensi')->name('request.download.presensi');
Route::get('/download-notulensi', 'RequestController@downloadNotulensi')->name('request.download.notulensi');
Route::get('/upload-NotulensinPresensi', 'RequestController@uploadNotulensinPresensi')->name('request.upload.NotulensinPresensi');
Route::post('/upload-dokumen', 'RequestController@storeLaporanRapat')->name('request.store.LaporanRapat');


// ==================================================================================
// 2. ROUTE AUTH (LOGIN/LOGOUT)
// ==================================================================================
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


// ==================================================================================
// 3. ROUTE DASHBOARD ADMIN (BUTUH LOGIN: SUPER ADMIN & ADMIN BARANG)
// ==================================================================================
Route::group([
    'middleware' => ['auth', 'role:super_admin,admin_barang'], 
    'prefix' => 'dashboard'
], function () {

    // Dashboard Utama
    Route::get('/', 'AdminDashboardController@index')->name('dashboard.index');

    // Manajemen Barang (Resource)
    Route::resource('barang', 'ItemController');
    Route::post('barang/add-stock', 'ItemController@addStock')->name('barang.addStock');

    // --- Group Approval ---
    Route::group(['prefix' => 'approvals'], function () {

        // Approval Barang
        Route::get('barang', 'RequestController@index')->name('requests.index');
        Route::post('barang/{reqBarang}/reject', 'RequestController@reject')->name('requests.reject');
        Route::post('barang/{reqBarang}/approve', 'RequestController@approve')->name('requests.approve');

        // Approval Zoom
        Route::get('zoom', 'ZoomRequestController@index')->name('zoom.requests.index');
        Route::post('zoom/{reqZoom}/reject', 'ZoomRequestController@reject')->name('zoom.requests.reject');
        Route::post('zoom/{reqZoom}/approve', 'ZoomRequestController@approve')->name('zoom.requests.approve');

        // Approval Catering
        Route::get('catering', 'CateringController@index')->name('catering.index');
        Route::post('catering/{catering}/reject', 'CateringController@reject')->name('catering.reject');
        Route::post('catering/{catering}/approve', 'CateringController@approve')->name('catering.approve');
        
        // Approval Kendaraan (FITUR BARU)
        Route::get('kendaraan', 'PeminjamanKendaraanController@index')->name('approvals.kendaraan');
        Route::get('kendaraan/{id}', 'PeminjamanKendaraanController@show')->name('kendaraan.show');
        Route::post('kendaraan/approve/{id}', 'PeminjamanKendaraanController@approve')->name('kendaraan.approve');
        Route::post('kendaraan/reject/{id}', 'PeminjamanKendaraanController@reject')->name('kendaraan.reject');
    });

    // === MASTER DATA KENDARAAN (CRUD MOBIL - FITUR BARU) ===
    Route::get('data-kendaraan', 'PeminjamanKendaraanController@listKendaraan')->name('kendaraan.index');
    Route::post('data-kendaraan', 'PeminjamanKendaraanController@storeKendaraan')->name('kendaraan.store_unit');
    Route::put('data-kendaraan/{id}', 'PeminjamanKendaraanController@updateKendaraan')->name('kendaraan.update');
    Route::delete('data-kendaraan/{id}', 'PeminjamanKendaraanController@destroyKendaraan')->name('kendaraan.destroy');

    // Setting / Template / Response
    Route::group(['prefix' => 'settings'], function () {
        Route::get('template', 'SettingController@templateIndex')->name('template.index');
        Route::post('template/update', 'SettingController@updateTemplate')->name('template.update');
        Route::get('response', 'SettingController@responseIndex')->name('response.index');
    });

    // Transaksi
    Route::get('transaksi', 'TransactionController@index')->name('transaksi.index');

    // Manajemen Dokumen (Admin side)
    Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
        Route::get('/', 'LaporanRapatController@index')->name('index');
        Route::post('/', 'LaporanRapatController@store')->name('store');
        Route::put('/{id}', 'LaporanRapatController@update')->name('update');
        Route::get('/{id}/preview', 'LaporanRapatController@preview')->name('preview');
        Route::get('/{id}/download', 'LaporanRapatController@download')->name('download');
        Route::post('/{id}/verify', 'LaporanRapatController@verify')->name('verify');
        Route::delete('/{id}', 'LaporanRapatController@destroy')->name('destroy');
    });

});

// ==================================================================================
// 4. SUPER ADMIN ROUTES
// ==================================================================================
Route::group([
    'middleware' => ['auth', 'role:super_admin'], 
    'prefix' => 'super', 
    'as' => 'super.'
], function () {
    Route::resource('users', 'UserController');
});


// ==================================================================================
// 5. HOME / FALLBACK
// ==================================================================================
Route::get('/home', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if (in_array($role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }
    }
    return redirect()->route('landing-page');
})->name('home');


// ==================================================================================
// 6. EXPORT DATA
// ==================================================================================
Route::get('/export/barang', 'ExportController@exportBarang')->name('export.barang');
Route::get('/export/transaksi', 'ExportController@exportTransaksi')->name('export.transactions');
Route::get('/export/dokumen', 'ExportController@exportDokumen')->name('export.dokumen');