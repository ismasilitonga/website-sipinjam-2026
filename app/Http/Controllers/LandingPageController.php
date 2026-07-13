<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Ormawa;
use Carbon\Carbon;

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
        $sekarang = Carbon::now();
        $bulanSekarang = $sekarang->format('Y-m');

        $kandidat = User::where('nim', $request->nim)
            ->orWhere('email', $request->email)
            ->get();

        $akunBentrok = $kandidat->first(function ($u) {
            if ($u->status === 'pending') {
                return true;
            }
            if ($u->status === 'aktif') {
                $batas = $u->batasAkhirJabatan();
                return $batas === null || $batas->isFuture() || $batas->isToday();
            }
            return false;
        });

        $akunLama = $kandidat->first(function ($u) {
            if (in_array($u->status, ['ditolak', 'nonaktif'])) {
                return true;
            }
            $batas = $u->batasAkhirJabatan();
            return $batas !== null && $batas->isPast();
        });

        $request->validate([
            'nama'            => 'required|string|max:255',
            'nim'             => [
                'required',
                'digits:10',
                function ($attribute, $value, $fail) use ($akunBentrok) {
                    if ($akunBentrok && $akunBentrok->nim === $value) {
                        $fail('NIM ini sudah aktif atau sedang menunggu validasi.');
                    }
                },
            ],
            'email'           => [
                'required', 'email',
                function ($attribute, $value, $fail) use ($akunBentrok) {
                    if ($akunBentrok && $akunBentrok->email === $value) {
                        $fail('Email ini sudah aktif atau sedang menunggu validasi.');
                    }
                },
            ],
            'role'            => 'required|in:anggota,ketua',
            'organisasi'      => 'required|string|max:255',
            'periode_mulai'   => 'required|date_format:Y-m',
            'periode_selesai' => [
                'required',
                'date_format:Y-m',
                'after_or_equal:periode_mulai',
                function ($attribute, $value, $fail) use ($bulanSekarang) {
                    if ($value < $bulanSekarang) {
                        $fail('Periode kepengurusan sudah berakhir. Bulan/tahun selesai tidak boleh sebelum bulan ini.');
                    }
                },
            ],
            'password'        => 'required|confirmed|min:8',
            'bukti_ktm'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bukti_sk'        => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'nama.required'                 => 'Nama lengkap wajib diisi.',
            'nama.max'                      => 'Nama lengkap maksimal 255 karakter.',
            'nim.required'                  => 'NIM wajib diisi.',
            'nim.digits'                    => 'NIM harus terdiri dari 10 digit angka.',
            'email.required'                => 'Email wajib diisi.',
            'email.email'                   => 'Format email tidak valid.',
            'role.required'                 => 'Peran wajib dipilih.',
            'role.in'                       => 'Peran yang dipilih tidak valid.',
            'organisasi.required'           => 'Organisasi wajib dipilih.',
            'periode_mulai.required'        => 'Bulan & tahun mulai periode kepengurusan wajib diisi.',
            'periode_mulai.date_format'     => 'Format bulan mulai tidak valid.',
            'periode_selesai.required'      => 'Bulan & tahun selesai periode kepengurusan wajib diisi.',
            'periode_selesai.date_format'   => 'Format bulan selesai tidak valid.',
            'periode_selesai.after_or_equal'=> 'Bulan/tahun selesai tidak boleh lebih awal dari bulan/tahun mulai.',
            'password.required'             => 'Kata sandi wajib diisi.',
            'password.confirmed'            => 'Konfirmasi kata sandi tidak sama.',
            'password.min'                  => 'Kata sandi minimal 8 karakter.',
            'bukti_ktm.required'            => 'Bukti KTM wajib diunggah.',
            'bukti_ktm.file'                => 'Bukti KTM harus berupa file.',
            'bukti_ktm.mimes'               => 'Bukti KTM harus berformat JPG, PNG, atau PDF.',
            'bukti_ktm.max'                 => 'Ukuran bukti KTM maksimal 5MB.',
            'bukti_sk.required'             => 'Bukti SK organisasi wajib diunggah.',
            'bukti_sk.file'                 => 'Bukti SK harus berupa file.',
            'bukti_sk.mimes'                => 'Bukti SK harus berformat JPG, PNG, atau PDF.',
            'bukti_sk.max'                  => 'Ukuran bukti SK maksimal 5MB.',
        ]);

        $mulai   = Carbon::createFromFormat('Y-m', $request->periode_mulai);
        $selesai = Carbon::createFromFormat('Y-m', $request->periode_selesai);

        if ($mulai->diffInMonths($selesai) > 36) {
            return back()
                ->withInput()
                ->withErrors([
                    'periode_selesai' => 'Rentang periode kepengurusan maksimal 3 tahun (36 bulan).'
                ]);
        }

        $organisasi = strtoupper($request->organisasi);

        $pathKtm = $request->file('bukti_ktm')->store('bukti-pendaftaran/ktm', 'public');
        $pathSk  = $request->file('bukti_sk')->store('bukti-pendaftaran/sk', 'public');

        $dataBaru = [
            'nama'                  => $request->nama,
            'nim'                   => $request->nim,
            'email'                 => $request->email,
            'role'                  => $request->role,
            'organisasi'            => $organisasi,
            'periode_mulai'         => $mulai->year,
            'periode_mulai_bulan'   => $mulai->month,
            'periode_selesai'       => $selesai->year,
            'periode_selesai_bulan' => $selesai->month,
            'password'              => Hash::make($request->password),
            'bukti_ktm'             => $pathKtm,
            'bukti_sk'              => $pathSk,
            'status'                => 'pending',
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