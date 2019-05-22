<?php

use Illuminate\Database\Seeder;

class ErrorCodeTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('error_codes')->insert([
            'code' => '1001',
            'description' => 'product or product family not found',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('error_codes')->insert([
            'code' => '1003',
            'description' => 'channel definition not found',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('error_codes')->insert([
            'code' => '1005',
            'description' => 'payment type not found',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('error_codes')->insert([
            'code' => '1400',
            'description' => 'ORA-01400: cannot insert NULL into (PREPAIDBILL" TRANSACTION ND)',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('error_codes')->insert([
            'code' => '1830',
            'description' => 'ORA-01830: date format picture ends before converting entire input string',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
