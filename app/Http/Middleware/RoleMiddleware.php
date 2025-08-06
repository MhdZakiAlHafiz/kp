<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\WithoutErrorHandler;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$userRole): Response
    {

        if (!Auth::check()) {
            // Jika pengguna belum login, redirect ke halaman login
            return redirect('/')->with([
                'message' => 'Anda harus login terlebih dahulu.',
                'alert-type' => 'warning',
            ])->withInput();
        }

        $roleName = Role::find(Auth::user()->role_id)->name ?? null;
        if (!in_array($roleName, $userRole)) {
            // Jika pengguna belum login, redirect ke halaman login
            return back();
        }

        return $next($request);
    }
}
