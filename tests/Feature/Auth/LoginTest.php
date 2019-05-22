<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    // Trait refresh database agar migration dijalankan
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        // // Kita memiliki 1 user terdaftar
        // $user = factory(User::class)->create([
        //     'nik'       => '7777777',
        //     'name'      => 'Client 7',
        //     'password'  => bcrypt('7777777'),
        //     'id_role'   => '1'
        // ]);

        // // Kunjungi halaman '/login'
        // $this->visit('/login');

        // // Submit form login dengan email dan password yang tepat
        // $this->submitForm('Login', [
        //     'nik'    => '7777777',
        //     'password' => '7777777',
        // ]);

        // // Lihat halaman ter-redirect ke url '/home' (login sukses).
        // $this->seePageIs('/panel');

        // // Kita melihat halaman tulisan "Dashboard" pada halaman itu.
        // $this->seeText('Dashboard');
    }
}
