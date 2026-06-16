<?php
// ============================================================
// FILE: app/Http/Controllers/Anggota/DetailLaporanController.php
// ============================================================
namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Insiden;

class DetailLaporanController extends Controller
{
    public function show($id)
    {
        $insiden = Insiden::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('anggota.detail-laporan', compact('insiden'));
    }
}
