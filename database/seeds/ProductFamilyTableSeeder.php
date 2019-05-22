<?php

use Illuminate\Database\Seeder;

class ProductFamilyTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('product_families')->insert([
            'name' => 'IHQUOTA',
            'description' => 'Indihome Quota',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('product_families')->insert([
            'name' => 'POD',
            'description' => 'Premium On Demand',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('product_families')->insert([
            'name' => 'MINIPACK',
            'description' => 'UseeTV Minipack',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('product_families')->insert([
            'name' => 'CONFCALL',
            'description' => 'Conference Call',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('product_families')->insert([
            'name' => 'BOD',
            'description' => 'Bandwidth On Demand',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('product_families')->insert([
            'name' => 'HSIPREPAID',
            'description' => 'High Speed Internet',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
