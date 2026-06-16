<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DetailAkunController extends Controller
{
    public function show()
    {
        return view('anggota.profil', [
            'user' => Auth::user()
        ]);
    }

    public function cancel($id)
{
    $peminjaman = PeminjamanRuangan::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('status', 'menunggu_ketua')
        ->firstOrFail();

    $peminjaman->delete();

    return redirect()->route('anggota.riwayat-ruangan')
        ->with('success', 'Pengajuan berhasil dibatalkan.');
}

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$request->filled('new_password')) {
            return back()->with('success', 'Tidak ada perubahan yang disimpan.');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ])->withInput();
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}