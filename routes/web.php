<?php

use App\Http\Controllers\data_proyekController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.home');
});
Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::get('/generate-nomor-cr/{jenis_surat}', [data_proyekController::class, 'generateNomorCr']);

// Memberikan nama 'data_proyek.index' pada route ini
Route::get('/data_proyek', [data_proyekController::class, 'index'])->name('data_proyek.index');
Route::get('/data_proyek/create', [data_proyekController::class, 'create']);
Route::get('/data_proyek/{id}', [data_proyekController::class, 'edit']);
Route::post('/data_proyek', [data_proyekController::class, 'store']);
Route::put('/data_proyek/{id}', [data_proyekController::class, 'update']);
Route::delete('/data_proyek/{id}', [data_proyekController::class, 'destroy']); // Perbaikan: menggunakan destroy bukan delete

Route::get('/data_proyek/{id}/kegiatan_detail', [data_proyekController::class, 'kegiatanDetail'])->name('data_proyek.kegiatan_detail');
Route::post('/data_proyek/{id}/kegiatan_detail', [data_proyekController::class, 'updateKegiatanDetail'])->name('data_proyek.kegiatan_detail.update');
