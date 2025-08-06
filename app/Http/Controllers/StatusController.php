<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Validation\Rules\Password;

class StatusController extends Controller
{
    //================================================
    // METHOD UNTUK MANAJEMEN USER (ADMIN)
    //================================================

    public function index(Request $request)
    {
        $query = User::where('status', 'submitted')
            ->where('id', '!=', Auth::id());

        // Logika Pencarian
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $users = $query->latest()->paginate(5);

        return view('pages.status.status', compact('users'));
    }

    public function approve(User $user)
    {
        try {
            $user->status = 'approved';
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User ' . $user->name . ' telah disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    public function reject(User $user)
    {
        try {
            $user->status = 'rejected';
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User ' . $user->name . ' telah ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    public function daftarAkun(Request $request)
    {
        $query = User::where('status', '!=', 'submitted')
            ->where('id', '!=', Auth::id());


        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $processedUsers = $query->latest()->paginate(5);

        return view('pages.status.status', compact('processedUsers'));
    }

    public function ubahStatus(Request $request, $id)
    {
        // Cari user, jika tidak ketemu akan gagal (404)
        $user = User::findOrFail($id);

        // Validasi tambahan: pastikan admin tidak menonaktifkan akunnya sendiri
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mengubah status akun Anda sendiri.'
            ], 403); // 403 Forbidden
        }

        try {
            // Toggle status
            $newStatus = ($user->status == 'approved') ? 'rejected' : 'approved';
            $user->status = $newStatus;
            $user->save();

            // Pesan sukses dinamis
            $pesan = ($newStatus == 'approved') ? 'diaktifkan' : 'dinonaktifkan';

            // Kembalikan response JSON yang sukses
            return response()->json([
                'success' => true,
                'message' => 'Akun ' . $user->name . ' berhasil ' . $pesan . '.',
                'new_status' => $newStatus,
                'status_text' => ($newStatus == 'approved') ? 'Aktif' : 'Nonaktif'
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error saat menyimpan
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
            ], 500); // 500 Internal Server Error
        }
    }

    public function hapus($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();
        return redirect()->route('daftar-akun')->with('sukses', 'Akun ' . $userName . ' berhasil dihapus secara permanen.');
    }

    public function profile_view()
    {
        $user = Auth::user();
        return view('pages.profile.index', compact('user'));
    }

    public function changePassword_view()
    {
        return view('pages.profile.change-password');
    }

    /**
     * Memproses permintaan untuk mengubah kata sandi pengguna.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('sukses', 'Kata sandi berhasil diubah. Silakan login kembali.');
    }
}
