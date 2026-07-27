<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Ruangan;

class InformatisBarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $jenis  = $request->input('jenis');
        $lantai = (string) auth()->user()->lantai_pic;

        $query = Barang::query()
            ->where(function ($q) use ($lantai) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
                  ->orWhereNull('ruangan_id');
            });

        if ($request->sumber == 'pic') {
            $query->where('kode', 'like', 'SC-%');
        } elseif ($request->sumber == 'ormawa') {
            $query->where('kode', 'not like', 'SC-%');
        }

        if ($jenis == 'bisa_dipinjam') $query->where('jenis_barang', 'bisa_dipinjam');
        if ($jenis == 'arsip')         $query->where('jenis_barang', 'arsip');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('jenis_barang', 'like', "%{$search}%");
            });
        }

        $barang = $query->latest()->paginate(10)->withQueryString();

        return view('pic.barang.index', compact('barang', 'search', 'jenis'));
    }

    public function create()
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $ruangans = Ruangan::where('lantai', $lantai)
            ->orderBy('nama_ruangan')
            ->get();

        return view('pic.barang.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $request->validate([
            'nama'         => 'required|string|max:255',
            'kode'         => 'required|string|unique:barang,kode',
            'kategori'     => 'nullable|string|max:100',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|max:2048',
            'kondisi'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'jenis_barang' => 'nullable|in:bisa_dipinjam,arsip',
            'ruangan_id'   => [
                'required',
                'exists:ruangan,id',
                function ($attribute, $value, $fail) use ($lantai) {
                    $valid = Ruangan::where('id', $value)->where('lantai', $lantai)->exists();
                    if (! $valid) {
                        $fail('Ruangan yang dipilih tidak berada di lantai Anda.');
                    }
                },
            ],
        ]);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('barang', 'public')
            : null;

        Barang::create([
            ...$request->except('foto'),
            'foto'         => $fotoPath,
            'jenis_barang' => $request->input('jenis_barang') === 'arsip' ? 'arsip' : 'bisa_dipinjam',
        ]);

        return redirect()->route('pic.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $barang = Barang::where(function ($q) use ($lantai) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
                  ->orWhereNull('ruangan_id');
            })
            ->findOrFail($id);

        $ruangans = Ruangan::where('lantai', $lantai)
            ->orderBy('nama_ruangan')
            ->get();

        return view('pic.barang.edit', compact('barang', 'ruangans'));
    }

    public function update(Request $request, $id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $barang = Barang::where(function ($q) use ($lantai) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
                  ->orWhereNull('ruangan_id');
            })
            ->findOrFail($id);

        $request->validate([
            'nama'         => 'required|string|max:255',
            'kode'         => 'required|string|unique:barang,kode,' . $id,
            'kategori'     => 'nullable|string|max:100',
            'stok'         => 'required|integer|min:0',
            'satuan'       => 'required|string|max:50',
            'deskripsi'    => 'nullable|string',
            'foto'         => 'nullable|image|max:2048',
            'kondisi'      => 'required|in:baik,rusak_ringan,rusak_berat',
            'jenis_barang' => 'nullable|in:bisa_dipinjam,arsip',
            'ruangan_id'   => [
                'required',
                'exists:ruangan,id',
                function ($attribute, $value, $fail) use ($lantai) {
                    $valid = Ruangan::where('id', $value)->where('lantai', $lantai)->exists();
                    if (! $valid) {
                        $fail('Ruangan yang dipilih tidak berada di lantai Anda.');
                    }
                },
            ],
        ]);

        $data = $request->except('foto');
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }
        $data['jenis_barang'] = $request->input('jenis_barang') === 'arsip' ? 'arsip' : 'bisa_dipinjam';

        $barang->update($data);

        return redirect()->route('pic.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lantai = (string) auth()->user()->lantai_pic;

        $barang = Barang::where(function ($q) use ($lantai) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('lantai', $lantai))
                  ->orWhereNull('ruangan_id');
            })
            ->findOrFail($id);

        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus.');
    }
}