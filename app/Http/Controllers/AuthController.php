<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(Request $request, string $role)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'password'   => ['required'],
        ]);

        $fieldMap = [
            'anggota' => 'nim',
            'ketua'   => 'nim',
            'pic'     => 'nim',
            'pamdal'  => 'nim',
            'admin'   => 'email',
        ];

        $field = $fieldMap[$role] ?? 'username';

        $credentials = [
            $field     => $request->identifier,
            'password' => $request->password,
            'role'     => $role,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors(['identifier' => 'Identitas atau kata sandi salah.']);
        }

        $user = Auth::user();

        if ($user->status === 'pending') {
            Auth::logout();
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors([
                    'identifier' => 'Akun Anda masih menunggu validasi Admin Student Center.'
                ]);
        }

        if ($user->status === 'ditolak') {
            Auth::logout();
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors([
                    'identifier' => 'Pendaftaran Anda telah ditolak. Silakan hubungi Admin Student Center.'
                ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole($role);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'nim'         => 'required|string|unique:users,nim',
            'password'    => 'required|min:8|confirmed',
            'role'        => 'required|in:anggota,ketua',
            'organisasi'  => 'required|string|max:255',
        ]);

        User::create([
            'name'       => $request->nama,
            'nim'        => $request->nim,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'organisasi' => $request->organisasi,
            'status'     => 'pending',
        ]);

        return redirect()->route('landingpage.pilih-login')
            ->with('success', 'Pendaftaran berhasil! Tunggu konfirmasi admin sebelum dapat masuk.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landingpage')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectByRole(string $role): \Illuminate\Http\RedirectResponse
    {
        return match ($role) {
            'anggota' => redirect()->route('anggota.dashboard'),
            'ketua'   => redirect()->route('ketua.dashboard'),
            'pic'     => redirect()->route('pic.dashboard'),
            'pamdal'  => redirect()->route('pamdal.dashboard'),
            'admin'   => redirect()->route('admin.dashboard'),
            default   => redirect()->route('masuk'),
        };
    }
}