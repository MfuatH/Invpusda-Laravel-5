<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ==========================================================
// 1. ROUTE PUBLIC (LANDING PAGE & REQUESTS)
// ==========================================================

Route::get('/', 'RequestController@landingPage')->name('landing-page');
Route::get('/request-barang', 'RequestController@createBarang')->name('request.barang.create');
Route::post('/request-barang', 'RequestController@storeBarang')->name('request.barang.store');
Route::get('/request-link-zoom', 'RequestController@createZoom')->name('request.zoom.create');
Route::post('/request-link-zoom', 'RequestController@storeZoom')->name('request.zoom.store');
Route::get('/request-konsumsi', 'RequestController@createKonsumsi')->name('request.konsumsi.create');
Route::get('/dashboard-doc', 'RequestController@dashboardDoc')->name('documents.dashboard_doc');    
Route::get('/undangan-upload', 'RequestController@createUndangan')->name('request.undangan.create');
Route::get('/download-presensi', 'RequestController@downloadPresensi')->name('request.download.presensi');
Route::get('/download-notulensi', 'RequestController@downloadNotulensi')->name('request.download.notulensi');
Route::get('/upload-NotulensinPresensi', 'RequestController@uploadNotulensinPresensi')->name('request.upload.NotulensinPresensi');
Route::post('/upload-dokumen', 'RequestController@storeLaporanRapat')->name('request.store.LaporanRapat');
Route::get('/catering/success', 'CateringController@successPage')->name('catering.success');

// ==========================================================
// 2. ROUTE AUTH (untuk Laravel 5 manual login/register)
// ==========================================================
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


// ==========================================================
// 3. ROUTE DASHBOARD ADMIN (SUPER ADMIN & ADMIN BARANG)
// ==========================================================
Route::group(['middleware' => ['auth', 'role:super_admin,admin_barang'], 'prefix' => 'dashboard'], function () {

    // Dashboard
    Route::get('/', 'AdminDashboardController@index')->name('dashboard.index');

    // Barang
    Route::resource('barang', 'ItemController');
    Route::post('barang/add-stock', 'ItemController@addStock')->name('barang.addStock');

    // Approval
    Route::group(['prefix' => 'approvals'], function () {
        Route::get('barang', 'RequestController@index')->name('requests.index');
        Route::post('barang/{reqBarang}/reject', 'RequestController@reject')->name('requests.reject');
        Route::post('barang/{reqBarang}/approve', 'RequestController@approve')->name('requests.approve');

        Route::get('zoom', 'ZoomRequestController@index')->name('zoom.requests.index');
        Route::post('zoom/{reqZoom}/reject', 'ZoomRequestController@reject')->name('zoom.requests.reject');
        Route::post('zoom/{reqZoom}/approve', 'ZoomRequestController@approve')->name('zoom.requests.approve');
    });

    // Pengaturan (SettingController)
    Route::group(['prefix' => 'settings'], function () {
        Route::get('template', 'SettingController@templateIndex')->name('template.index');
        Route::post('template/update', 'SettingController@updateTemplate')->name('template.update');
        Route::get('response', 'SettingController@responseIndex')->name('response.index');
    });

    // Transaksi
    Route::get('transaksi', 'TransactionController@index')->name('transaksi.index');

    // Catering Management
    Route::resource('catering', 'CateringController'); 

    // Laporan Rapat / Document Management
    Route::group(['prefix' => 'documents', 'as' => 'documents.'], function () {
        Route::get('/', 'LaporanRapatController@index')->name('index');
        Route::get('/{id}/download', 'LaporanRapatController@download')->name('download');
        Route::post('/{id}/verify', 'LaporanRapatController@verify')->name('verify');
        Route::delete('/{id}', 'LaporanRapatController@destroy')->name('destroy');
    });
});

// ==========================================================
// 4. SUPER ADMIN ROUTES
// ==========================================================
Route::group(['middleware' => ['auth', 'role:super_admin'], 'prefix' => 'super', 'as' => 'super.'], function () {
    Route::resource('users', 'UserController');
});

// ==========================================================
// 5. HOME / FALLBACK
// ==========================================================
Route::get('/home', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if (in_array($role, ['super_admin', 'admin_barang'])) {
            return redirect()->route('dashboard.index');
        }
    }
    return redirect()->route('landing-page');
})->name('home');

// ==========================================================
// 6. EXPORT DATA
// ==========================================================
Route::get('/export/barang', 'ExportController@exportBarang')->name('export.barang');
Route::get('/export/transaksi', 'ExportController@exportTransaksi')->name('export.transactions');
