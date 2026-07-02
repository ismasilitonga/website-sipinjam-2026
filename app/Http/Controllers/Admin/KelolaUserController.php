<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KelolaUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('status', 'aktif')
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
                ])
            );
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.pengguna.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'nim'        => 'required|string|unique:users,nim',
            'password'   => 'required|min:8',
            'role'       => 'required|in:anggota,ketua,pic,admin,pamdal',
            'organisasi' => 'required|string|max:255',
        ]);

        User::create([
            'nama'       => $request->nama,
            'email'      => $request->email,
            'nim'        => $request->nim,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'organisasi' => $request->organisasi,
            'status'     => 'aktif',
        ]);

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
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'email' => 'required|email',
            'nim'   => 'required|string',
            'role'       => 'required|in:anggota,ketua,pic,admin,pamdal',
            'organisasi' => 'required|string|max:255',
            'password'   => 'nullable|min:8',
        ]);

        $user->nama       = $request->nama;
        $user->email      = $request->email;
        $user->nim        = $request->nim;
        $user->role       = $request->role;
        $user->organisasi = $request->organisasi;

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