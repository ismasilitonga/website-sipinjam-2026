<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ormawa;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaOrmawaController extends Controller
{
    public function index()
    {
        $ormawas = Ormawa::withCount([
    'users as jumlah_anggota' => function ($q) {
        $q->whereIn('role', ['anggota', 'ketua']);
    }
])->latest()->get();

        return view('admin.ormawa.index', compact('ormawas'));
    }

    public function create()
    {
        return view('admin.ormawa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'singkatan'     => 'required|string|max:20|unique:ormawas,singkatan',
            'nama_organisasi' => 'nullable|string|max:255',
            'kontak'          => 'nullable|string|max:20',
            'deskripsi'       => 'nullable|string|max:500',
            'status'          => 'required|in:aktif,nonaktif',
        ]);

        Ormawa::create($request->only('singkatan', 'nama_organisasi', 'kontak', 'deskripsi', 'status'));

        return redirect()->route('admin.ormawa.index')
            ->with('success', 'Ormawa berhasil ditambahkan.');
    }

    public function show($id)
{
    $ormawa = Ormawa::findOrFail($id);

    $anggota = User::where('organisasi', $ormawa->singkatan)
        ->whereIn('role', ['anggota', 'ketua'])
        ->get();

    return view('admin.ormawa.show', compact('ormawa', 'anggota'));
}

    public function edit($id)
    {
        $ormawa = Ormawa::findOrFail($id);
        return view('admin.ormawa.edit', compact('ormawa'));
    }

    public function update(Request $request, $id)
    {
        $ormawa = Ormawa::findOrFail($id);

        $request->validate([
            'singkatan'     => 'required|string|max:20|unique:ormawas,singkatan,' . $id,
            'nama_organisasi' => 'nullable|string|max:255',
            'kontak'          => 'nullable|string|max:20',
            'deskripsi'       => 'nullable|string|max:500',
            'status'          => 'required|in:aktif,nonaktif',
        ]);

        $ormawa->update($request->only('singkatan', 'nama_organisasi', 'kontak', 'deskripsi', 'status'));

        return redirect()->route('admin.ormawa.index')
            ->with('success', 'Data ormawa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ormawa = Ormawa::findOrFail($id);

        User::where('ormawa_id', $id)->update(['ormawa_id' => null]);

        $ormawa->delete();

        return back()->with('success', 'Ormawa berhasil dihapus.');
    }
}