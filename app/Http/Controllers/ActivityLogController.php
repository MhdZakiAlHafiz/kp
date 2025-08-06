<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity; // <-- PENTING: Import model Activity

class ActivityLogController extends Controller
{
    /**
     * Menampilkan halaman log aktivitas.
     */
    public function index()
    {
        // Ambil semua data aktivitas
        $activities = Activity::with('causer', 'subject') // Eager load relasi untuk performa
            ->latest() // Urutkan dari yang paling baru
            ->paginate(20); // Gunakan paginasi agar halaman tidak berat

        // Kirim data ke view
        return view('admin.log_aktivitas', compact('activities'));
    }
}