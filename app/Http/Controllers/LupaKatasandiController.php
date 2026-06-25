<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class LupaKatasandiController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar di sistem.'], 404);
        }

        $otp = rand(100000, 999999);

        Cache::put('otp_' . $request->email, $otp, now()->addMinutes(5));

        Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($m) use ($request) {
            $m->to($request->email)->subject('Kode OTP Reset Kata Sandi - SiPinjam');
        });

        return response()->json(['success' => true, 'message' => 'OTP berhasil dikirim.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah atau sudah kadaluarsa.'], 422);
        }

        Cache::put('otp_verified_' . $request->email, true, now()->addMinutes(10));

        return response()->json(['success' => true, 'message' => 'OTP valid.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);

        $verified = Cache::get('otp_verified_' . $request->email);

        if (!$verified) {
            return response()->json(['success' => false, 'message' => 'Sesi verifikasi tidak valid. Ulangi dari awal.'], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        Cache::forget('otp_' . $request->email);
        Cache::forget('otp_verified_' . $request->email);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diubah.']);
    }
}