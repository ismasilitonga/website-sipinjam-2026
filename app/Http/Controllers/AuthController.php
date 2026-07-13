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

        /** @var \App\Models\User $user */
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

        if ($user->status === 'nonaktif') {
            Auth::logout();
            return back()
                ->withInput($request->only('identifier'))
                ->withErrors([
                    'identifier' => 'Akun Anda sudah tidak aktif, masa kepengurusan telah berakhir/digantikan. Hubungi Admin jika ada kekeliruan.'
                ]);
        }
        if (
    in_array($user->role, ['anggota', 'ketua']) &&
    $user->masaJabatanBerakhir()
    ) 
    {
    Auth::logout();
    $user->update(['status' => 'nonaktif']);
    return back()
        ->withInput($request->only('identifier'))
        ->withErrors([
            'identifier' => 'Masa jabatan Anda telah berakhir pada '
                . $user->batasAkhirJabatan()->translatedFormat('F Y')
                . '. Silakan hubungi Admin Student Center jika ada kekeliruan.'
        ]);
    }

        $request->session()->regenerate();

        return $this->redirectByRole($role);
    }

    public function store(Request $request)
    {
        $tahunSekarang = (int) date('Y');

        $request->validate([
            'nama'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'nim'             => 'required|string|unique:users,nim',
            'password'        => 'required|min:8|confirmed',
            'role'            => 'required|in:anggota,ketua',
            'organisasi'      => 'required|string|max:255',
            'periode_mulai'   => 'required|integer|digits:4|min:2000',
            'periode_selesai' => "required|integer|digits:4|min:2000|gte:periode_mulai",
            'bukti_ktm'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048|required_without:bukti_sk',
            'bukti_sk'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048|required_without:bukti_ktm',
        ], [
            'bukti_ktm.required_without' => 'Wajib mengunggah minimal salah satu bukti (KTM atau SK).',
            'bukti_sk.required_without'  => 'Wajib mengunggah minimal salah satu bukti (KTM atau SK).',
            'periode_selesai.gte'        => 'Tahun selesai tidak boleh lebih kecil dari tahun mulai.',
        ]);

        $pathKtm = null;
        if ($request->hasFile('bukti_ktm')) {
            $pathKtm = $request->file('bukti_ktm')->store('bukti-pendaftaran/ktm', 'public');
        }

        $pathSk = null;
        if ($request->hasFile('bukti_sk')) {
            $pathSk = $request->file('bukti_sk')->store('bukti-pendaftaran/sk', 'public');
        }

        User::create([
            'nama'            => $request->nama,
            'nim'             => $request->nim,
            'email'           => $request->email,
            'password'        => bcrypt($request->password),
            'role'            => $request->role,
            'organisasi'      => $request->organisasi,
            'periode_mulai'   => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'bukti_ktm'       => $pathKtm,
            'bukti_sk'        => $pathSk,
            'status'          => 'pending',
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