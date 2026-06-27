<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\KodeOtp;

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

KodeOtp::where('email', $request->email)->delete();

KodeOtp::create([
    'email'       => $request->email,
    'otp'         => $otp,
    'is_verified' => false,
    'expires_at'  => now()->addMinutes(5),
]);

        
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

        $otpRecord = KodeOtp::where('email', $request->email)
                            ->where('otp', $request->otp)
                            ->where('is_verified', false)
                            ->first();

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah atau sudah kadaluarsa.'], 422);
        }

        if (now()->greaterThan($otpRecord->expires_at)) {
            $otpRecord->delete();
            return response()->json(['success' => false, 'message' => 'Kode OTP sudah kadaluarsa.'], 422);
        }

        $otpRecord->update(['is_verified' => true]);

        return response()->json(['success' => true, 'message' => 'OTP valid.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $otpRecord = KodeOtp::where('email', $request->email)
                            ->where('is_verified', true)
                            ->first();

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'Sesi verifikasi tidak valid. Ulangi dari awal.'], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $otpRecord->update(['is_verified' => true]);

        return response()->json(['success' => true, 'message' => 'Kata sandi berhasil diubah.']);
    }
}