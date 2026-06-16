<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('landingpage.pilih-login');
        }

        $userRole = strtolower(Auth::user()->role);

        if (!in_array($userRole, array_map('strtolower', $roles))) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        $user = Auth::user();

        if ($user->status === 'pending') {
            Auth::logout();
            return redirect()->route('landingpage.pilih-login')
                ->with('loginError', 'Akun Anda masih menunggu validasi Admin Student Center.');
        }

        if ($user->status === 'ditolak') {
            Auth::logout();
            return redirect()->route('landingpage.pilih-login')
                ->with('loginError', 'Pendaftaran Anda telah ditolak. Silakan hubungi Admin Student Center.');
        }

        return $next($request);
    }
}