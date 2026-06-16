<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class KelolaBarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::orderBy('kode', 'asc')->paginate(10);
        return view('Admin.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('Admin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'kode'     => 'required|string|unique:barang,kode',
            'kategori' => 'nullable|string|max:100',
            'stok'     => 'required|integer|min:0',
            'satuan'   => 'required|string|max:50',
            'kondisi'  => 'required|in:baik,rusak_ringan,rusak_berat',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('barang', 'public')
            : null;

        Barang::create([...$request->except('foto'), 'foto' => $fotoPath]);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('Admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'kode'     => 'required|string|unique:barang,kode,' . $id,
            'kategori' => 'nullable|string|max:100',
            'stok'     => 'required|integer|min:0',
            'satuan'   => 'required|string|max:50',
            'kondisi'  => 'required|in:baik,rusak_ringan,rusak_berat',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang->update($data);
        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}