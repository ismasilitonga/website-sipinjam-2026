<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ruangan;

class KelolaRuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('kode', 'asc')->paginate(10);
        return view('Admin.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('Admin.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan'      => 'required|string|max:255',
            'kode'      => 'required|string|unique:ruangan,kode',
            'gedung'    => 'nullable|string|max:100',
            'lantai'    => 'nullable|string|max:10',
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string',
            'foto'      => 'nullable|image|max:2048',
            'status'    => 'required|in:tersedia,tidak_tersedia',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('ruangan', 'public')
            : null;

        Ruangan::create([...$request->except('foto'), 'foto' => $fotoPath]);

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('Admin.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'nama_ruangan'      => 'required|string|max:255',
            'kode'      => 'required|string|unique:ruangan,kode,' . $id,
            'gedung'    => 'nullable|string|max:100',
            'lantai'    => 'nullable|string|max:10',
            'kapasitas' => 'required|integer|min:0',
            'fasilitas' => 'nullable|string',
            'foto'      => 'nullable|image|max:2048',
            'status'    => 'required|in:tersedia,tidak_tersedia',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ruangan', 'public');
        }

        $ruangan->update($data);
        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Ruangan::findOrFail($id)->delete();
        return back()->with('success', 'Ruangan berhasil dihapus.');
    }
}