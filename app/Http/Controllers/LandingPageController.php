<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Ormawa;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('landingpage.index');
    }

    public function tentang()
    {
        return view('landingpage.tentang');
    }

    public function panduan()
    {
        return view('landingpage.panduan');
    }

    public function register()
    {
        $ormawas = Ormawa::where('status', 'aktif')
            ->orderBy('singkatan')
            ->get();

        return view('landingpage.register', compact('ormawas'));
    }

    public function store(Request $request)
    {
        $tahunSekarang = (int) date('Y');

        $akunLama = User::where(function ($q) use ($request) {
                $q->where('nim', $request->nim)
                  ->orWhere('email', $request->email);
            })
            ->where(function ($q) use ($tahunSekarang) {
                $q->whereIn('status', ['ditolak', 'nonaktif'])
                  ->orWhere('periode_selesai', '<', $tahunSekarang);
            })
            ->first();

        $akunBentrok = User::where(function ($q) use ($request) {
                $q->where('nim', $request->nim)
                  ->orWhere('email', $request->email);
            })
            ->where(function ($q) use ($tahunSekarang) {
                $q->where('status', 'pending')
                  ->orWhere(function ($q2) use ($tahunSekarang) {
                      $q2->where('status', 'aktif')
                         ->where('periode_selesai', '>=', $tahunSekarang);
                  });
            })
            ->first();

        $request->validate([
            'nama'            => 'required|string|max:255',
            'nim'             => [
                'required',
                function ($attribute, $value, $fail) use ($akunBentrok) {
                    if ($akunBentrok && $akunBentrok->nim === $value) {
                        $fail('NIM ini masih aktif menjabat atau sedang menunggu validasi.');
                    }
                },
            ],
            'email'           => [
                'required', 'email',
                function ($attribute, $value, $fail) use ($akunBentrok) {
                    if ($akunBentrok && $akunBentrok->email === $value) {
                        $fail('Email ini masih aktif menjabat atau sedang menunggu validasi.');
                    }
                },
            ],
            'role'            => 'required|in:anggota,ketua',
            'organisasi'      => 'required|string|max:255',
            'periode_mulai'   => 'required|integer|digits:4|min:2000|max:' . ($tahunSekarang + 1),
            'periode_selesai' => 'required|integer|digits:4|gte:periode_mulai|min:' . $tahunSekarang,
            'password'        => 'required|confirmed|min:8',
            'bukti_ktm'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bukti_sk'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'nama.required'            => 'Nama lengkap wajib diisi.',
            'nama.max'                 => 'Nama lengkap maksimal 255 karakter.',
            'nim.required'             => 'NIM wajib diisi.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'role.required'            => 'Peran wajib dipilih.',
            'role.in'                  => 'Peran yang dipilih tidak valid.',
            'organisasi.required'      => 'Organisasi wajib dipilih.',
            'periode_mulai.required'   => 'Tahun mulai periode kepengurusan wajib diisi.',
            'periode_mulai.digits'     => 'Tahun mulai harus 4 digit, contoh: 2024.',
            'periode_mulai.max'        => 'Tahun mulai tidak boleh lebih dari ' . ($tahunSekarang + 1) . '.',
            'periode_selesai.required' => 'Tahun selesai periode kepengurusan wajib diisi.',
            'periode_selesai.digits'   => 'Tahun selesai harus 4 digit, contoh: 2026.',
            'periode_selesai.gte'      => 'Tahun selesai tidak boleh lebih kecil dari tahun mulai.',
            'periode_selesai.min'      => 'Periode kepengurusan sudah berakhir. Tahun selesai minimal ' . $tahunSekarang . ' (tahun ini).',
            'password.required'       => 'Kata sandi wajib diisi.',
            'password.confirmed'      => 'Konfirmasi kata sandi tidak sama.',
            'password.min'            => 'Kata sandi minimal 8 karakter.',
            'bukti_ktm.required'      => 'Bukti KTM wajib diunggah.',
            'bukti_ktm.file'          => 'Bukti KTM harus berupa file.',
            'bukti_ktm.mimes'         => 'Bukti KTM harus berformat JPG, PNG, atau PDF.',
            'bukti_ktm.max'           => 'Ukuran bukti KTM maksimal 5MB.',
            'bukti_sk.required'       => 'Bukti SK organisasi wajib diunggah.',
            'bukti_sk.file'           => 'Bukti SK harus berupa file.',
            'bukti_sk.mimes'          => 'Bukti SK harus berformat JPG, PNG, atau PDF.',
            'bukti_sk.max'            => 'Ukuran bukti SK maksimal 5MB.',
        ]);
        $organisasi = strtoupper($request->organisasi);

        if (($request->periode_selesai - $request->periode_mulai) > 3) {
            return back()
                ->withInput()
                ->withErrors([
                    'periode_selesai' => 'Rentang periode kepengurusan maksimal 3 tahun.'
                ]);
        }

        $pathKtm = $request->file('bukti_ktm')->store('bukti-pendaftaran/ktm', 'public');
        $pathSk  = $request->file('bukti_sk')->store('bukti-pendaftaran/sk', 'public');

        $dataBaru = [
            'nama'            => $request->nama,
            'nim'             => $request->nim,
            'email'           => $request->email,
            'role'            => $request->role,
            'organisasi'      => $organisasi,
            'periode_mulai'   => $request->periode_mulai,
            'periode_selesai' => $request->periode_selesai,
            'password'        => Hash::make($request->password),
            'bukti_ktm'       => $pathKtm,
            'bukti_sk'        => $pathSk,
            'status'          => 'pending',
        ];

        if ($akunLama) {
            $akunLama->update($dataBaru);
        } else {
            User::create($dataBaru);
        }

        return redirect()
            ->route('landingpage.pilih-login')
            ->with('success', 'Pendaftaran berhasil. Menunggu validasi Admin.');
    }

    public function pilihLogin()
    {
        return view('landingpage.pilih-login');
    }

    public function pilihAdmin()
    {
        return view('admin.masuk');
    }

    public function pilihAnggota()
    {
        return view('anggota.masuk');
    }

    public function pilihKetua()
    {
        return view('ketua.masuk');
    }

    public function pilihPamdal()
    {
        return view('pamdal.masuk');
    }

    public function pilihPic()
    {
        return view('pic.masuk');
    }
}