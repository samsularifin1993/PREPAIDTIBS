<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('users')->insert([
            'nik' => '9876543',
            'name' => 'Administrator',
            'password' => bcrypt('admin1234'),
            'id_role' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'nik' => '8765432',
            'name' => 'Finance',
            'password' => bcrypt('fin1234'),
            'id_role' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'nik' => '7654321',
            'name' => 'Marketing',
            'password' => bcrypt('mark1234'),
            'id_role' => '3',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
