<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Ormawa;
use App\Notifications\AkunDibuatAdmin;

class KelolaUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status', 'aktif');

        $query = User::query()
            ->when($status !== 'semua', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%$search%")
                       ->orWhere('email', 'like', "%$search%")
                       ->orWhere('nim', 'like', "%$search%")
                       ->orWhere('organisasi', 'like', "%$search%")
                       ->orWhere('role', 'like', "%$search%");
                });
            })
            ->latest();

        if ($request->ajax()) {
            return response()->json(
                $query->get()->map(fn($u) => [
                    'id'         => $u->id,
                    'nama'       => $u->nama,
                    'nim'        => $u->nim,
                    'email'      => $u->email,
                    'organisasi' => $u->organisasi ?? '-',
                    'role'       => $u->role,
                    'status'     => $u->status,
                ])
            );
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.pengguna.index', compact('users', 'search', 'status'));
    }

    public function create()
{
    $ormawas = Ormawa::where('status', 'aktif')
        ->orderBy('nama_organisasi')
        ->get();

    $units = Ormawa::where('status', 'aktif')
        ->where('punya_pic', true)
        ->orderBy('nama_organisasi')
        ->get();

    return view('admin.pengguna.create', compact('ormawas', 'units'));
}

    public function store(Request $request)
{
    $isBerorganisasi = in_array($request->role, ['anggota', 'ketua', 'pic']);
    $wajibDokumen    = in_array($request->role, ['anggota', 'ketua']);

    $request->validate([
        'nama'       => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'nim'        => 'required|string|unique:users,nim',
        'password'   => 'required|min:8',
        'role'       => 'required|in:anggota,ketua,pic,admin,pamdal',
        'organisasi' => [
            Rule::requiredIf($isBerorganisasi),
            'nullable', 'string', 'max:255',
            Rule::exists('ormawa', 'singkatan'),
        ],
        'lantai_pic' => [
            Rule::requiredIf($request->role === 'pic'),
            'nullable', 'integer', 'min:1', 'max:20',
        ],
        'bukti_ktm'  => [
            Rule::requiredIf($wajibDokumen),
            'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120',
        ],
        'bukti_sk'   => [
            Rule::requiredIf($wajibDokumen),
            'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120',
        ],
    ], [
        'bukti_ktm.required' => 'Bukti KTM wajib diunggah untuk role Anggota/Ketua.',
        'bukti_sk.required'  => 'Bukti SK organisasi wajib diunggah untuk role Anggota/Ketua.',
    ]);

    $pathKtm = null;
    if ($request->hasFile('bukti_ktm')) {
        $pathKtm = $request->file('bukti_ktm')->store('bukti-pendaftaran/ktm', 'public');
    }

    $pathSk = null;
    if ($request->hasFile('bukti_sk')) {
        $pathSk = $request->file('bukti_sk')->store('bukti-pendaftaran/sk', 'public');
    }

    $user = User::create([
        'nama'       => $request->nama,
        'email'      => $request->email,
        'nim'        => $request->nim,
        'password'   => Hash::make($request->password),
        'role'       => $request->role,
        'organisasi' => $isBerorganisasi ? strtoupper($request->organisasi) : null,
        'lantai_pic' => $request->role === 'pic' ? $request->lantai_pic : null,
        'bukti_ktm'  => $pathKtm,
        'bukti_sk'   => $pathSk,
        'status'     => 'aktif',
    ]);

    $user->notify(new AkunDibuatAdmin($user));

    return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
}

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pengguna.show', compact('user'));
    }

    public function edit($id)
{
    $user = User::findOrFail($id);

    $ormawas = Ormawa::where('status', 'aktif')
        ->orderBy('nama_organisasi')
        ->get();

    $units = Ormawa::where('status', 'aktif')
        ->where('punya_pic', true)
        ->orderBy('nama_organisasi')
        ->get();

    return view('admin.pengguna.edit', compact('user', 'ormawas', 'units'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $isBerorganisasi = in_array($request->role, ['anggota', 'ketua', 'pic']);

    $request->validate([
        'nama'                  => 'required|string|max:255',
        'email'                 => 'required|email|unique:users,email,' . $user->id,
        'nim'                   => 'required|string|unique:users,nim,' . $user->id,
        'role'                  => 'required|in:anggota,ketua,pic,admin,pamdal',
        'organisasi'            => [
            Rule::requiredIf($isBerorganisasi),
            'nullable', 'string', 'max:255',
            Rule::exists('ormawa', 'singkatan'),
        ],
        'lantai_pic'            => [
            Rule::requiredIf($request->role === 'pic'),
            'nullable', 'integer', 'min:1', 'max:20',
        ],
        'bukti_ktm'             => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'bukti_sk'              => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'status'                => 'required|in:aktif,nonaktif,pending,ditolak',
        'periode_mulai'         => 'nullable|date_format:Y-m',
        'periode_selesai'       => 'nullable|date_format:Y-m|after_or_equal:periode_mulai',
        'password'              => 'nullable|min:8',
    ], [
        'periode_selesai.after_or_equal' => 'Bulan/tahun selesai tidak boleh lebih awal dari bulan/tahun mulai.',
    ]);

    $user->nama       = $request->nama;
    $user->email      = $request->email;
    $user->nim        = $request->nim;
    $user->role       = $request->role;
    $user->organisasi = $isBerorganisasi ? strtoupper($request->organisasi) : null;
    $user->lantai_pic = $request->role === 'pic' ? $request->lantai_pic : null;
    $user->status     = $request->status;

    if ($request->hasFile('bukti_ktm')) {
        $user->bukti_ktm = $request->file('bukti_ktm')->store('bukti-pendaftaran/ktm', 'public');
    }

    if ($request->hasFile('bukti_sk')) {
        $user->bukti_sk = $request->file('bukti_sk')->store('bukti-pendaftaran/sk', 'public');
    }

    if ($request->filled('periode_mulai')) {
        $mulai = \Carbon\Carbon::createFromFormat('Y-m', $request->periode_mulai);
        $user->periode_mulai       = $mulai->year;
        $user->periode_mulai_bulan = $mulai->month;
    } else {
        $user->periode_mulai       = null;
        $user->periode_mulai_bulan = null;
    }

    if ($request->filled('periode_selesai')) {
        $selesai = \Carbon\Carbon::createFromFormat('Y-m', $request->periode_selesai);
        $user->periode_selesai       = $selesai->year;
        $user->periode_selesai_bulan = $selesai->month;
    } else {
        $user->periode_selesai       = null;
        $user->periode_selesai_bulan = null;
    }

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();
    return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
}

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}