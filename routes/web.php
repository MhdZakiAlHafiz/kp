<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\data_proyekController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Rute Terbuka (Untuk Semua, Termasuk yang Belum Login)
|--------------------------------------------------------------------------
*/

// ... (rute login, register, logout tidak diubah) ...
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'registerView']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Rute yang Hanya Bisa Diakses Jika Sudah Login
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ... (rute dashboard dan data_proyek tidak diubah) ...
    Route::get('/dashboard', [data_proyekController::class, 'dashboard'])->name('dashboard');

})->name('dashboard');
Route::get('/generate-nomor-cr/{jenis_surat}', [data_proyekController::class, 'generateNomorCr']);
Route::prefix('data_proyek')->name('data_proyek.')->group(function () {
    Route::get('/', [data_proyekController::class, 'index'])->name('index');
    Route::get('/create', [data_proyekController::class, 'create']);
    Route::post('/', [data_proyekController::class, 'store']);
    Route::get('/{id}', [data_proyekController::class, 'edit']);
    Route::put('/{id}', [data_proyekController::class, 'update']);
    Route::delete('/{id}', [data_proyekController::class, 'destroy']);
    Route::get('/{id}/kegiatan_detail', [data_proyekController::class, 'kegiatanDetail'])->name('kegiatan_detail');
    Route::post('/{id}/kegiatan_detail', [data_proyekController::class, 'updateKegiatanDetail'])->name('kegiatan_detail.update');
    Route::get('/{id}/pdf', [data_proyekController::class, 'generatePDF'])->name('pdf');
    Route::post('/update-status/{id}', [data_proyekController::class, 'updateStatusAjax'])->name('update_status_ajax');

});

Route::get('/profile', [StatusController::class, 'profile_view'])->name('profile');
Route::get('/change-password', [StatusController::class, 'changePassword_view'])->name('change-password');
Route::post('/change-password', [StatusController::class, 'updatePassword'])->name('change-password.update');



/*
|--------------------------------------------------------------------------
| Rute Khusus Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['role:admin'])->group(function () {
    // --- RUTE MANAJEMEN AKUN ---
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::patch('/status/{user}/approve', [StatusController::class, 'approve'])->name('status.approve');
    Route::patch('/status/{user}/reject', [StatusController::class, 'reject'])->name('status.reject');
    Route::get('/daftar-akun', [StatusController::class, 'daftarAkun'])->name('daftar-akun');
    Route::post('/akun/ubah-status/{id}', [StatusController::class, 'ubahStatus'])->name('akun.ubah-status');
    Route::delete('/akun/hapus/{id}', [StatusController::class, 'hapus'])->name('akun.hapus');

    // --- RUTE MANAJEMEN MASTER DATA ---
    Route::prefix('admin/master')->name('admin.master.')->group(function () {
        // Halaman untuk menampilkan form tambah data
        Route::get('/create', [MasterDataController::class, 'create'])->name('create');

        // Halaman untuk menampilkan & mengelola data
        Route::get('/manage', [MasterDataController::class, 'manage'])->name('manage');

        // Proses Penyimpanan Data
        Route::post('/jenis-surat', [MasterDataController::class, 'storeJenisSurat'])->name('jenis_surat.store');
        Route::post('/owner', [MasterDataController::class, 'storeOwner'])->name('owner.store');
        Route::post('/jenis-proyek', [MasterDataController::class, 'storeJenisProyek'])->name('jenis_proyek.store');
        Route::post('/pic-dev', [MasterDataController::class, 'storePicDev'])->name('pic_dev.store');
        Route::post('/pic-plan', [MasterDataController::class, 'storePicPlan'])->name('pic_plan.store');

        // Proses Hapus Data
        Route::delete('/jenis-surat/{id}', [MasterDataController::class, 'destroyJenisSurat'])->name('jenis_surat.destroy');
        Route::delete('/owner/{id}', [MasterDataController::class, 'destroyOwner'])->name('owner.destroy');
        Route::delete('/jenis-proyek/{id}', [MasterDataController::class, 'destroyJenisProyek'])->name('jenis_proyek.destroy');
        Route::delete('/pic-dev/{id}', [MasterDataController::class, 'destroyPicDev'])->name('pic_dev.destroy');
        Route::delete('/pic-plan/{id}', [MasterDataController::class, 'destroyPicPlan'])->name('pic_plan.destroy');
    });

    // --- KOREKSI: RUTE LOG AKTIVITAS SEHARUSNYA DI SINI ---
    // Dipindahkan keluar dari grup 'admin/master' agar nama dan URL-nya benar.
    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.aktivitas');
});
