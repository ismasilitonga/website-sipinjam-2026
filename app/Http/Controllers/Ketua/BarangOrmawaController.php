<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangOrmawaController extends Controller
{
    private function getKategoris(): \Illuminate\Support\Collection
    {
        return Barang::select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');
    }

    // ─── INDEX ────────────────────────────────────────────────────
    public function index(Request $request)
{
    $search = $request->input('search');
    $sumber = $request->input('sumber');

    $query = Barang::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('kode', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%");
        });
    }

    if ($sumber == 'ormawa') {
        $query->whereNotNull('organisasi');
    }

    if ($sumber == 'pic') {
        $query->whereNull('organisasi');
    }

    $barang = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('ketua.barang-ormawa.index', compact(
        'barang',
        'search',
        'sumber'
    ));
}

    // ─── CREATE ───────────────────────────────────────────────────
    public function create()
    {
        $kategoris = $this->getKategoris();
        return view('ketua.barang-ormawa.create', compact('kategoris'));
    }

    // ─── STORE ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string|max:100',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required|string|max:50',
            'kondisi'   => 'required|in:baik,rusak_ringan,rusak_berat',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string|max:500',
        ]);

      $singkatan = auth()->user()->organisasi;

        $last = Barang::where('kode', 'like', $singkatan . '-%')
        ->orderByDesc('id')->first();

        $num = $last? ((int) substr($last->kode, strlen($singkatan) + 1)) + 1: 1;
        $data['kode'] = $singkatan . '-' . str_pad($num, 2, '0', STR_PAD_LEFT);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }
        $data['organisasi'] = auth()->user()->organisasi;

        Barang::create($data);

        return redirect()
            ->route('ketua.barang-ormawa.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // ─── EDIT ─────────────────────────────────────────────────────
   public function edit(Barang $barang_ormawa)
{
    if (empty($barang_ormawa->organisasi)) {
        return redirect()
            ->route('ketua.barang-ormawa.index')
            ->with('error', 'Barang dari PIC tidak dapat diedit di sini.');
    }

    $kategoris = $this->getKategoris();

    return view('ketua.barang-ormawa.edit', [
        'barang' => $barang_ormawa,
        'kategoris' => $kategoris,
    ]);
}

    // ─── UPDATE ───────────────────────────────────────────────────
    public function update(Request $request, Barang $barang_ormawa)
{
    abort_if(
        empty($barang_ormawa->organisasi),
        403,
        'Barang dari PIC tidak dapat diubah.'
    );

    $data = $request->validate([
        'nama'      => 'required|string|max:255',
        'kategori'  => 'required|string|max:100',
        'stok'      => 'required|integer|min:0',
        'satuan'    => 'required|string|max:50',
        'kondisi'   => 'required|in:baik,rusak_ringan,rusak_berat',
        'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'deskripsi' => 'nullable|string|max:500',
    ]);

    if ($request->hasFile('foto')) {
        if ($barang_ormawa->foto) {
            Storage::disk('public')->delete($barang_ormawa->foto);
        }

        $data['foto'] = $request->file('foto')->store('barang', 'public');
    }

    $barang_ormawa->update($data);

    return redirect()
        ->route('ketua.barang-ormawa.index')
        ->with('success', 'Barang berhasil diperbarui.');
}

    // ─── DESTROY ──────────────────────────────────────────────────
    public function destroy(Barang $barang_ormawa)
{
    abort_if(
        empty($barang_ormawa->organisasi),
        403,
        'Barang dari PIC tidak dapat dihapus.'
    );

    if ($barang_ormawa->foto) {
        Storage::disk('public')->delete($barang_ormawa->foto);
    }

    $barang_ormawa->delete();

    return redirect()
        ->route('ketua.barang-ormawa.index')
        ->with('success', 'Barang berhasil dihapus.');
}
}