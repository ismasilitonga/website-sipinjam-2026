<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DetailAkunAdminController extends Controller
{
    public function show()
    {
        return view('admin.profil', ['user' => Auth::user()]);
    }
    public function update(Request $request)
{
    $user = \App\Models\User::find(Auth::id()); 

    $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|email|unique:users,email,' . $user->id,
        'new_password' => 'nullable|min:8|confirmed',
    ]);

    $user->name  = $request->name;
    $user->email = $request->email;

    if ($request->filled('new_password')) {
        $user->password = Hash::make($request->new_password);
    }
    $user->save();
    return back()->with('success', 'Profil berhasil diperbarui!');
}
}