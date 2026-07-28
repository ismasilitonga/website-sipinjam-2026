<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Ruangan;

class KelolaBarangController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $sumber     = $request->input('sumber');
        $jenis      = $request->input('jenis');       
        $organisasi = $request->input('organisasi');  

        $query = Barang::with('ruangan')->orderBy('kode', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($sumber === 'pic')    $query->whereNull('organisasi');
        if ($sumber === 'ormawa') $query->whereNotNull('organisasi');

        if ($jenis === 'bisa_dipinjam') $query->where('jenis_barang', 'bisa_dipinjam');
        if ($jenis === 'arsip')         $query->where('jenis_barang', 'arsip');

        if (!empty($organisasi)) {
            $query->where('organisasi', $organisasi);
        }

        $barang = $query->paginate(10)->withQueryString();

        $daftarOrganisasi = Barang::whereNotNull('organisasi')
            ->where('organisasi', '!=', '')
            ->distinct()
            ->orderBy('organisasi')
            ->pluck('organisasi');

        return view('Admin.barang.index', compact(
            'barang', 'search', 'sumber', 'jenis', 'organisasi', 'daftarOrganisasi'
        ));
    }

    public function create()
    {
        $ruangans = Ruangan::orderBy('lantai')->orderBy('nama_ruangan')->get();
        return view('Admin.barang.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:255',
            'kode'         => 'required|string|unique:barang,kode',
            'kategori'     => 'nullable|string|max:100',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
            'kondisi'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'ruangan_id'   => 'required|exists:ruangan,id',
            'jenis_barang' => 'required|in:bisa_dipinjam,arsip',
            'foto'         => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('barang', 'public')
            : null;

        Barang::create([
            ...$request->except('foto'),
            'foto'   => $fotoPath,
            'sumber' => 'admin',
        ]);

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $ruangans = Ruangan::orderBy('lantai')->orderBy('nama_ruangan')->get();
        return view('Admin.barang.edit', compact('barang', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'kode'         => 'required|string|unique:barang,kode,' . $id,
            'kategori'     => 'nullable|string|max:100',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
            'kondisi'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'ruangan_id'   => 'required|exists:ruangan,id',
            'jenis_barang' => 'required|in:bisa_dipinjam,arsip',
            'foto'         => 'nullable|image|max:2048',
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