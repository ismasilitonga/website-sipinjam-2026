<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    $request->validate([
        'nama'       => 'required|string|max:255',
        'nim'        => 'required|unique:users,nim',
        'email'      => 'required|email|unique:users,email',
        'role'       => 'required|in:anggota,ketua',
        'organisasi' => 'required|string|max:255',
        'password'   => 'required|confirmed|min:8',
    ]);

    User::create([
        'nama'       => $request->nama,
        'nim'        => $request->nim,
        'email'      => $request->email,
        'role'       => $request->role,
        'organisasi' => $request->organisasi,
        'password'   => Hash::make($request->password),
        'status'     => 'pending',
    ]);

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