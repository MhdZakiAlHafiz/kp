<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            return back();
        }
        return view('pages.data_proyek.auth.login');
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'approved') {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kirim error untuk akun yang belum disetujui
                return back()->withErrors(['email' => 'Akun Anda belum disetujui oleh Admin.']);
            }
        }

        // Kirim error untuk kredensial yang salah
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function registerView()
    {
        if (Auth::check()) {
            return back();
        }
        return view('pages.data_proyek.auth.register');
    }

    public function register(Request $request)
    {
        if (Auth::check()) {
            return back();
        }

        // Menggunakan Validator manual agar bisa mengontrol redirect
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Jika validasi gagal, kembali ke halaman login dengan pesan error
        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator);
        }

        $userRole = Role::where('name', 'user')->first();
        $userRoleId = $userRole ? $userRole->id : 2;

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $userRoleId,
            'status' => 'submitted',
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('sukses', 'Registrasi berhasil! Silakan tunggu persetujuan dari Admin.');
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
