<?php

use App\Http\Controllers\AiController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTE PUBLIK (Bisa Diakses Siapa Saja)
// ==========================================
Route::get('/', function () {
    return view('landing');
});

// Fitur Konsultasi AI & Booking
Route::get('/konsultasi', [AiController::class, 'index']);
Route::post('/kirim-aduan', [AiController::class, 'store']);
Route::post('/konfirmasi-booking/{id}', [AiController::class, 'konfirmasiBooking']);

// Fitur Tracking Antrean Pelanggan
Route::get('/cek-status', [AiController::class, 'halamanCekStatus']);
Route::post('/cek-status', [AiController::class, 'prosesCekStatus']);

// Ganti Alur Autentikasi Teknisi
Route::get('/admin/login', [AiController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AiController::class, 'login']);
Route::post('/admin/logout', [AiController::class, 'logout']);


// ==========================================
// 2. RUTE PRIVAT (Wajib Login Teknisi)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Dashboard Utama Admin
    Route::get('/admin', [AiController::class, 'adminDashboard']);
    
    // Proses Perubahan Status & Input Biaya Final (Pastikan URL ini sesuai dengan action form di admin.blade.php)
    Route::post('/admin/update-status/{id}', [AiController::class, 'updateStatus']);
});