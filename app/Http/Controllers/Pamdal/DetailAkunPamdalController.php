<?php

namespace App\Http\Controllers\Pamdal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DetailAkunPamdalController extends Controller
{
    public function show()
    {
        return view('pamdal.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'nim'          => 'required|string|unique:users,nim,' . $user->id,
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->nim   = $request->nim;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}