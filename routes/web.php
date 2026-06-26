<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LupaKatasandiController;

use App\Http\Controllers\Anggota\DashboardAnggotaController;
use App\Http\Controllers\Anggota\DaftarRuanganController;
use App\Http\Controllers\Anggota\DaftarBarangController;
use App\Http\Controllers\Anggota\PengajuanRuanganController;
use App\Http\Controllers\Anggota\PengajuanBarangController;
use App\Http\Controllers\Anggota\HandoverAnggotaController;
use App\Http\Controllers\Anggota\RiwayatRuanganAnggotaController;
use App\Http\Controllers\Anggota\RiwayatBarangController;
use App\Http\Controllers\Anggota\DetailRuanganController;
use App\Http\Controllers\Anggota\LaporInsidenController;
use App\Http\Controllers\Anggota\RiwayatInsidenController;
use App\Http\Controllers\Anggota\DetailLaporanController;
use App\Http\Controllers\Anggota\CheckinController;
use App\Http\Controllers\Anggota\CheckoutController;
use App\Http\Controllers\Anggota\DetailAkunController;
use App\Http\Controllers\Anggota\PengalihanBarangController;

use App\Http\Controllers\Ketua\DashboardKetuaController;
use App\Http\Controllers\Ketua\DaftarPengajuanController;
use App\Http\Controllers\Ketua\DetailAkunKetuaController;
use App\Http\Controllers\Ketua\RiwayatRuanganKetuaController;
use App\Http\Controllers\Ketua\BarangOrmawaController;

use App\Http\Controllers\Pic\DashboardPicController;
use App\Http\Controllers\Pic\DetailAkunPicController;
use App\Http\Controllers\Pic\TambahRuanganController;
use App\Http\Controllers\Pic\InformatisBarangController;
use App\Http\Controllers\Pic\ValidasiPengajuanController;
use App\Http\Controllers\Pic\TindakLanjutInsidenController;
use App\Http\Controllers\Pic\HandoverPicController;
use App\Http\Controllers\Pic\RiwayatRuanganPicController;
use App\Http\Controllers\Pic\RiwayatBarangPicController;

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DetailAkunAdminController;
use App\Http\Controllers\Admin\KelolaUserController;
use App\Http\Controllers\Admin\KelolaOrmawaController;
use App\Http\Controllers\Admin\RiwayatRuanganAdminController;
use App\Http\Controllers\Admin\ValidasiPendaftarController;
use App\Http\Controllers\Admin\KelolaRuanganController;
use App\Http\Controllers\Pamdal\DashboardPamdalController;
use App\Http\Controllers\Pamdal\DetailAkunPamdalController;
use App\Http\Controllers\Pamdal\KonfirmasiKunciController;


Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');
Route::get('/tentang', [LandingPageController::class, 'tentang'])->name('landingpage.tentang');
Route::get('/panduan', [LandingPageController::class, 'panduan'])->name('landingpage.panduan');
Route::get('/pilih-login', [LandingPageController::class, 'pilihLogin'])->name('landingpage.pilih-login');
Route::get('/register', [LandingPageController::class, 'register'])->name('landingpage.register');
Route::post('/register', [LandingPageController::class, 'store'])->name('register.store');
Route::get('/lupa-kata-sandi', function () {return view('landingpage.lupa-katasandi');})->name('password.request');
Route::post('/lupa-kata-sandi/kirim-otp',   [LupaKatasandiController::class, 'sendOtp'])->name('password.send-otp');
Route::post('/lupa-kata-sandi/verifikasi',   [LupaKatasandiController::class, 'verifyOtp'])->name('password.verify-otp');
Route::post('/lupa-kata-sandi/reset',        [LupaKatasandiController::class, 'resetPassword'])->name('password.reset');


Route::get('/pilih-login/admin',   [LandingPageController::class, 'pilihAdmin'])->name('landingpage.pilih-login.admin');
Route::get('/pilih-login/anggota', [LandingPageController::class, 'pilihAnggota'])->name('landingpage.pilih-login.anggota');
Route::get('/pilih-login/ketua',   [LandingPageController::class, 'pilihKetua'])->name('landingpage.pilih-login.ketua');
Route::get('/pilih-login/pamdal',  [LandingPageController::class, 'pilihPamdal'])->name('landingpage.pilih-login.pamdal');
Route::get('/pilih-login/pic',     [LandingPageController::class, 'pilihPic'])->name('landingpage.pilih-login.pic');

Route::post('/login/{role}', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout',       [AuthController::class, 'logout'])->name('logout');
Route::post('/daftar',       [AuthController::class, 'store'])->name('daftar.store');

Route::get('/test-flash', function () {
    return redirect()->route('landingpage.pilih-login')->with('success', 'Test pesan berhasil!');
});

Route::get('/cekphp', function () {
    return ['php_version'  => phpversion(),'openssl_cafile' => ini_get('openssl.cafile'),'curl_cainfo'  => ini_get('curl.cainfo'),];
});

Route::middleware(['auth'])->group(function () {
    Route::get('/redirect', [AuthController::class, 'redirectByRole'])->name('redirect');

    Route::middleware(['role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {

        Route::get('/dashboard', [DashboardAnggotaController::class, 'index'])->name('dashboard');

        Route::get('/profil',          [DetailAkunController::class, 'show'])->name('profil.show');
        Route::post('/profil/update',  [DetailAkunController::class, 'update'])->name('profil.update');

        Route::get('/daftar-ruangan',       [DaftarRuanganController::class, 'index'])->name('daftar-ruangan');
        Route::get('/daftar-ruangan/{id}',  [DetailRuanganController::class, 'show'])->name('detail-ruangan');
        Route::get('/daftar-barang',        [DaftarBarangController::class, 'index'])->name('daftar-barang');

        Route::get('/pengajuan-ruangan',              [PengajuanRuanganController::class, 'index'])->name('pengajuan-ruangan');
        Route::post('/pengajuan-ruangan',             [PengajuanRuanganController::class, 'store'])->name('pengajuan-ruangan.store');
        Route::delete('/pengajuan-ruangan/{id}/batal',[PengajuanRuanganController::class, 'cancel'])->name('pengajuan-ruangan.cancel');
        Route::get('/pengajuan-barang',               [PengajuanBarangController::class, 'index'])->name('pengajuan-barang');
        Route::post('/pengajuan-barang',              [PengajuanBarangController::class, 'store'])->name('pengajuan-barang.store');
        Route::get('/pengajuan-barang/cek-stok',      [PengajuanBarangController::class, 'cekStok'])->name('pengajuan-barang.cek-stok');
        
        Route::get('/handover',                         [HandoverAnggotaController::class, 'index'])->name('handover');
        Route::get('/pengalihan-barang',                [PengalihanBarangController::class, 'index'])->name('pengalihan-barang');
        Route::post('/pengalihan-barang',               [PengalihanBarangController::class, 'store'])->name('pengalihan-barang.store');
        Route::post('/pengalihan-barang/{id}/konfirmasi',[PengalihanBarangController::class, 'konfirmasi'])->name('pengalihan-barang.konfirmasi');

        Route::get('/lapor-insiden',    [LaporInsidenController::class, 'index'])->name('lapor-insiden');
        Route::post('/lapor-insiden',   [LaporInsidenController::class, 'store'])->name('lapor-insiden.store');
        Route::get('/riwayat-insiden',  [RiwayatInsidenController::class, 'index'])->name('riwayat-insiden');
        Route::get('/riwayat-insiden/{id}', [DetailLaporanController::class, 'show'])->name('detail-laporan');

        Route::get('/checkin',   [CheckinController::class, 'index'])->name('checkin');
        Route::post('/checkin',  [CheckinController::class, 'store'])->name('checkin.store');
        Route::get('/checkout',  [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/riwayat-ruangan', [RiwayatRuanganAnggotaController::class, 'index'])->name('riwayat-ruangan');
        Route::get('/riwayat-barang',  [RiwayatBarangController::class, 'index'])->name('riwayat-barang');
    });

    Route::middleware(['role:ketua'])->prefix('ketua')->name('ketua.')->group(function () {

        Route::get('/dashboard', [DashboardKetuaController::class, 'index'])->name('dashboard');

        Route::get('/profil',         [DetailAkunKetuaController::class, 'show'])->name('profil.show');
        Route::post('/profil/update', [DetailAkunKetuaController::class, 'update'])->name('profil.update');

        Route::get('/daftar-ruangan', [DaftarRuanganController::class, 'index'])->name('daftar-ruangan');
        Route::get('/daftar-barang',  [DaftarBarangController::class, 'index'])->name('daftar-barang');

        Route::get('/daftar-pengajuan',                    [DaftarPengajuanController::class, 'index'])->name('daftar-pengajuan');
        Route::post('/daftar-pengajuan/{id}/setujui',      [DaftarPengajuanController::class, 'setujui'])->name('pengajuan.setujui');
        Route::post('/daftar-pengajuan/{id}/tolak',        [DaftarPengajuanController::class, 'tolak'])->name('pengajuan.tolak');

        Route::get('/riwayat-peminjaman', [RiwayatRuanganKetuaController::class, 'index'])->name('riwayat-peminjaman');
        Route::get('/riwayat-peminjaman/{id}', [RiwayatRuanganKetuaController::class, 'show'])->name('riwayat-peminjaman.show');


        Route::get('/barang-ormawa/pilih-jenis', function () {return view('ketua.barang-ormawa.pilih-jenis');})->name('barang-ormawa.pilih-jenis');   
        Route::resource('barang-ormawa', BarangOrmawaController::class);
    });

    Route::middleware(['role:pic'])->prefix('pic')->name('pic.')->group(function () {

        Route::get('/dashboard', [DashboardPicController::class, 'index'])->name('dashboard');

        Route::get('/profil', [DetailAkunPicController::class, 'show'])->name('profil.show');

        Route::get('/daftar-ruangan', [DaftarRuanganController::class, 'index'])->name('daftar-ruangan');
        Route::get('/daftar-barang',  [DaftarBarangController::class, 'index'])->name('daftar-barang');

        Route::get('/ruangan',           [TambahRuanganController::class, 'index'])->name('ruangan.index');
        Route::get('/ruangan/tambah',    [TambahRuanganController::class, 'create'])->name('ruangan.create');
        Route::post('/ruangan',          [TambahRuanganController::class, 'store'])->name('ruangan.store');
        Route::get('/ruangan/{id}/edit', [TambahRuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('/ruangan/{id}',      [TambahRuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('/ruangan/{id}',   [TambahRuanganController::class, 'destroy'])->name('ruangan.destroy');

        Route::get('/barang',            [InformatisBarangController::class, 'index'])->name('barang.index');
        Route::get('/barang/tambah',     [InformatisBarangController::class, 'create'])->name('barang.create');
        Route::post('/barang',           [InformatisBarangController::class, 'store'])->name('barang.store');
        Route::get('/barang/{id}/edit',  [InformatisBarangController::class, 'edit'])->name('barang.edit');
        Route::put('/barang/{id}',       [InformatisBarangController::class, 'update'])->name('barang.update');
        Route::delete('/barang/{id}',    [InformatisBarangController::class, 'destroy'])->name('barang.destroy');

        Route::get('/daftar-pengajuan',                       [ValidasiPengajuanController::class, 'index'])->name('daftar-pengajuan');
        Route::post('/daftar-pengajuan/{id}/setujui',         [ValidasiPengajuanController::class, 'setujui'])->name('pengajuan.setujui');
        Route::post('/daftar-pengajuan/{id}/tolak',           [ValidasiPengajuanController::class, 'tolak'])->name('pengajuan.tolak');
        Route::post('/barang/{id}/setujui',                   [ValidasiPengajuanController::class, 'setujuiBarang'])->name('barang.setujui');
        Route::post('/barang/{id}/tolak',                     [ValidasiPengajuanController::class, 'tolakBarang'])->name('barang.tolak');

        Route::get('/serah-terima',                           [HandoverPicController::class, 'index'])->name('serah-terima');
        Route::post('/serah-terima/{id}/konfirmasi',          [HandoverPicController::class, 'konfirmasi'])->name('serah-terima.konfirmasi');
        Route::post('/terima-kembali/{id}',                   [HandoverPicController::class, 'terimaKembali'])->name('terima-kembali');

        Route::get('/laporan-insiden',        [TindakLanjutInsidenController::class, 'index'])->name('laporan-insiden');
        Route::post('/laporan-insiden/{id}',  [TindakLanjutInsidenController::class, 'update'])->name('laporan-insiden.update');
        Route::get('/laporan-insiden/excel',  [TindakLanjutInsidenController::class, 'exportExcel'])->name('laporan-insiden.excel');
        Route::get('/laporan-insiden/pdf',    [TindakLanjutInsidenController::class, 'exportPdf'])->name('laporan-insiden.pdf');

        Route::get('/status-peminjaman',      [ValidasiPengajuanController::class, 'status'])->name('status-peminjaman');
        Route::get('/status-peminjaman/{id}', [ValidasiPengajuanController::class, 'detail'])->name('status-peminjaman.detail');
        Route::get('/laporan/unduh',          [ValidasiPengajuanController::class, 'unduh'])->name('laporan.unduh');
        Route::get('/laporan/excel',          [ValidasiPengajuanController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/pdf',            [ValidasiPengajuanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/excel-barang',   [ValidasiPengajuanController::class, 'exportExcelBarang'])->name('laporan.excel-barang');
        Route::get('/laporan/pdf-barang',     [ValidasiPengajuanController::class, 'exportPdfBarang'])->name('laporan.pdf-barang');

        Route::get('/riwayat-peminjaman',     [RiwayatRuanganPicController::class, 'index'])->name('riwayat-peminjaman');
        Route::get('/riwayat-peminjaman/{id}',[RiwayatRuanganPicController::class, 'detail'])->name('riwayat-peminjaman.detail');
        Route::get('/riwayat-barang',         [RiwayatBarangPicController::class, 'index'])->name('riwayat.barang');
        Route::get('/riwayat-barang/{id}',     [RiwayatBarangPicController::class, 'detail'])->name('barang.detail'); 
        Route::get('/riwayat/export',[RiwayatRuanganPicController::class, 'export'])->name('riwayat.export');
        Route::get('/riwayat',[RiwayatRuanganPicController::class, 'index'])->name('riwayat.index');  
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

        Route::get('/profil',         [DetailAkunAdminController::class, 'show'])->name('profil.show');
        Route::post('/profil/update', [DetailAkunAdminController::class, 'update'])->name('profil.update');

        Route::get('/daftar-ruangan', [TambahRuanganController::class, 'index'])->name('daftar-ruangan');
        Route::get('/daftar-barang',  [DaftarBarangController::class, 'index'])->name('daftar-barang');

        Route::get('/ruangan',           [KelolaRuanganController::class, 'index'])->name('ruangan.index');
        Route::get('/ruangan/tambah',    [KelolaRuanganController::class, 'create'])->name('ruangan.create');
        Route::post('/ruangan',          [KelolaRuanganController::class, 'store'])->name('ruangan.store');
        Route::get('/ruangan/{id}/edit', [KelolaRuanganController::class, 'edit'])->name('ruangan.edit');
        Route::put('/ruangan/{id}',      [KelolaRuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('/ruangan/{id}',   [KelolaRuanganController::class, 'destroy'])->name('ruangan.destroy');

        Route::get('/laporan-insiden',       [TindakLanjutInsidenController::class, 'index'])->name('laporan-insiden');
        Route::get('/laporan-insiden/pdf',   [TindakLanjutInsidenController::class, 'exportPdf'])->name('laporan-insiden.pdf');
        Route::get('/laporan-insiden/excel', [TindakLanjutInsidenController::class, 'exportExcel'])->name('laporan-insiden.excel');

        Route::get('/laporan/unduh',        [ValidasiPendaftarController::class, 'unduhLaporan'])->name('laporan.unduh');
        Route::get('/laporan/excel',        [ValidasiPendaftarController::class, 'exportExcel'])->name('laporan.excel');
        Route::get('/laporan/pdf',          [ValidasiPendaftarController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/excel-barang', [ValidasiPendaftarController::class, 'exportExcelBarang'])->name('laporan.excel-barang');
        Route::get('/laporan/pdf-barang',   [ValidasiPendaftarController::class, 'exportPdfBarang'])->name('laporan.pdf-barang');

        Route::get('/riwayat-peminjaman', [RiwayatRuanganAdminController::class, 'index'])->name('riwayat-peminjaman');
        Route::get('/riwayat-peminjaman/export', [RiwayatRuanganAdminController::class, 'export'])->name('riwayat-peminjaman.export'); // ← tambahkan
        Route::get('/riwayat-peminjaman/{id}', [RiwayatRuanganAdminController::class, 'detail'])->name('riwayat-peminjaman.detail');

        Route::get('/status-peminjaman',       [DashboardAdminController::class, 'statusPeminjaman'])->name('status-peminjaman');
        Route::get('/status-peminjaman/{id}',  [DashboardAdminController::class, 'detailPeminjaman'])->name('status-peminjaman.detail');

        Route::resource('/pengguna', KelolaUserController::class);
        Route::resource('/ormawa',   KelolaOrmawaController::class);

        Route::get('/pendaftar',                [ValidasiPendaftarController::class, 'index'])->name('pendaftar.index');
        Route::post('/pendaftar/{id}/setujui',  [ValidasiPendaftarController::class, 'setujui'])->name('pendaftar.setujui');
        Route::post('/pendaftar/{id}/tolak',    [ValidasiPendaftarController::class, 'tolak'])->name('pendaftar.tolak');
    });

    Route::middleware(['role:pamdal'])->prefix('pamdal')->name('pamdal.')->group(function () {

        Route::get('/dashboard', [DashboardPamdalController::class, 'index'])->name('dashboard');

        Route::get('/profil', [DetailAkunPamdalController::class, 'show'])->name('profil.show');

        Route::get('/daftar-ruangan', [DaftarRuanganController::class, 'index'])->name('daftar-ruangan');
        Route::get('/daftar-barang',  [DaftarBarangController::class, 'index'])->name('daftar-barang');

        Route::get('/daftar-peminjaman',     [KonfirmasiKunciController::class, 'index'])->name('daftar-peminjaman');
        Route::post('/kunci/{id}/ambil',     [KonfirmasiKunciController::class, 'konfirmasiAmbil'])->name('kunci.ambil');
        Route::post('/kunci/{id}/kembalikan',[KonfirmasiKunciController::class, 'konfirmasiKembali'])->name('kunci.kembalikan');
    });

}); 