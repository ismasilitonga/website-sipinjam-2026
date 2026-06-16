<?php
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Insiden;

class RiwayatInsidenController extends Controller
{
    public function index()
    {
        $insidens = Insiden::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('anggota.riwayat-insiden', compact('insidens'));
    }
}
