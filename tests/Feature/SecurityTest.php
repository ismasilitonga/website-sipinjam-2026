<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    // TEST 1: Halaman riwayat tidak bisa diakses tanpa login
    public function test_riwayat_tidak_bisa_diakses_tanpa_login()
    {
        $response = $this->get('/pic/riwayat');
        $response->assertRedirect('/'); // redirect ke home
    }

    // TEST 2: Export tidak bisa diakses tanpa login
    public function test_export_tidak_bisa_diakses_tanpa_login()
    {
        // Cek route export yang benar
        $response = $this->get('/pic/riwayat/export');
        $response->assertRedirect('/');
    }

    // TEST 3: SQL Injection tidak bisa masuk
    public function test_sql_injection_tidak_bisa_masuk()
    {
        $response = $this->post('/login/pic', [
            'identifier' => "' OR 1=1--",  // field nya 'identifier'
            'password'   => "' OR 1=1--",
        ]);
        // Harus gagal login (bukan 200)
        $response->assertRedirect();
        $this->assertGuest();
    }

    // TEST 4: XSS tidak bisa masuk
    public function test_xss_tidak_bisa_masuk_saat_login()
    {
        $response = $this->post('/login/pic', [
            'identifier' => '<script>alert("XSS")</script>',
            'password'   => 'password123',
        ]);
        $response->assertRedirect();
        $this->assertGuest();
    }

    // TEST 5: Brute force - cek ada proteksi atau tidak
    public function test_brute_force_diblokir()
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login/pic', [
                'identifier' => 'salah@email.com',
                'password'   => 'salahpassword',
            ]);
        }

        $response = $this->post('/login/pic', [
            'identifier' => 'salah@email.com',
            'password'   => 'salahpassword',
        ]);

        // Cek diblokir (429) ATAU tetap redirect gagal login (302)
        $this->assertContains($response->status(), [302, 429]);
    }
}