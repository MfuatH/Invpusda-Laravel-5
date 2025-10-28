<?php

use Illuminate\Support\Facades\Route;

// Semua route otomatis masuk ke group middleware 'web' di Laravel 5.5

// 1. Landing Page (Halaman Utama)
Route::get('/', 'RequestController@landingPage')->name('landing-page');

// 2. Request Barang
Route::get('/request-barang', 'RequestController@createBarang')->name('request.barang.create');
Route::post('/request-barang', 'RequestController@storeBarang')->name('request.barang.store');

// 3. Request Link Zoom
Route::get('/request-link-zoom', 'RequestController@createZoom')->name('request.zoom.create');
Route::post('/request-link-zoom', 'RequestController@storeZoom')->name('request.zoom.store');

// 4. Otentikasi Laravel (Login / Register / Logout)
Auth::routes();

// 5. Dashboard Admin (opsional, bisa dikembangkan nanti)
Route::get('/home', 'HomeController@index')->name('home');
