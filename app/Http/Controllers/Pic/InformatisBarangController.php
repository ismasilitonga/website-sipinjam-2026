<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class InformatisBarangController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $query = Barang::query();

    if ($request->sumber == 'pic') {
    $query->where('kode', 'like', 'BRG-%');
    } elseif ($request->sumber == 'ormawa') {
    $query->where('kode', 'not like', 'BRG-%');
}

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%")
              ->orWhere('organisasi', 'like', "%{$search}%");
        });
    }
    $barang = $query->latest()->paginate(10);
    return view('pic.barang.index', compact('barang'));
}

    public function create()
    {
        return view('pic.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'kode'      => 'required|string|unique:barang,kode',
            'kategori'  => 'nullable|string|max:100',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|max:2048',
            'kondisi'   => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('barang', 'public')
            : null;

        Barang::create([...$request->except('foto'), 'foto' => $fotoPath]);

        return redirect()->route('pic.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('pic.barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'kode'      => 'required|string|unique:barang,kode,' . $id,
            'kategori'  => 'nullable|string|max:100',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto'      => 'nullable|image|max:2048',
            'kondisi'   => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang->update($data);
        return redirect()->route('pic.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();
        return back()->with('success', 'Barang berhasil dihapus.');
    }
}