<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FotoIdentitasController extends Controller
{

    public function show(CheckIn $checkIn): StreamedResponse
    {
        $peminjaman = $checkIn->peminjaman;
        $user = Auth::user();

        $bolehLihat = $peminjaman->user_id === $user->id
            || in_array($user->role, ['admin', 'pamdal']);

        abort_unless($bolehLihat, 403, 'Anda tidak berhak melihat file ini.');

        abort_unless(
            $checkIn->foto_ktp && Storage::disk('local')->exists($checkIn->foto_ktp),
            404
        );

        return Storage::disk('local')->response($checkIn->foto_ktp);
    }
}