<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DetailAkunPicController extends Controller
{
    public function show()
    {
        return view('pic.profil', ['user' => Auth::user()]);
    }

   public function update(Request $request)
{
    $user = \App\Models\User::findOrFail(Auth::id());

    $request->validate([
        'nama'         => 'required|string|max:255',
        'nim'          => 'required|string|unique:users,nim,' . $user->id,
        'email'        => 'required|email|unique:users,email,' . $user->id,
        'organisasi'   => 'nullable|string|max:255',
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user->nama       = $request->nama;
    $user->nim        = $request->nim;
    $user->email      = $request->email;
    $user->organisasi = $request->organisasi;

    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password);
    }
    $user->save();
    return back()->with('success', 'Profil berhasil diperbarui!');
}
}