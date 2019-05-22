<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('products')->insert(['name' => '5000','description' => 'IHQUOTA 5000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '10000','description' => 'IHQUOTA 10000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '20000','description' => 'IHQUOTA 20000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '50000','description' => 'IHQUOTA 50000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '75000','description' => 'IHQUOTA 75000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '100000','description' => 'IHQUOTA 100000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '175000','description' => 'IHQUOTA 175000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '310000','description' => 'IHQUOTA 310000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '450000','description' => 'IHQUOTA 450000','id_product_family' => '1','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '10 Mbps','description' => 'POD 10 Mbps','id_product_family' => '2','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '20 Mbps','description' => 'POD 20 Mbps','id_product_family' => '2','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '30 Mbps','description' => 'POD 30 Mbps','id_product_family' => '2','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '40 Mbps','description' => 'POD 40 Mbps','id_product_family' => '2','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '50 Mbps','description' => 'POD 50 Mbps','id_product_family' => '2','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINMV2S','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDIDYSD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINT2SD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINMV1S','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDS2S','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINT1SD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINHBLS','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDS1S','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEKIDDYS','description' => 'Minipack IndiKids','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINNWSD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINMV1H','description' => 'Minipack IndiMovie-1','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINMV2H','description' => 'Minipack IndiMovie-2','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINHBLH','description' => 'Minipack IndiMovie HBO','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDS1H','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDS2H','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEKIDDYH','description' => 'Minipack IndiKids','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINNWHD','description' => 'Minipack ','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINT1HD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINT2HD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'PREUSEEINDIDYHD','description' => 'Minipack','id_product_family' => '3','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '10 Mbps','description' => 'BOD 10 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '20 Mbps','description' => 'BOD 30 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '30 Mbps','description' => 'BOD 30 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '40 Mbps','description' => 'BOD 40 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '50 Mbps','description' => 'BOD 50 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => '100 Mbps','description' => 'BOD 100 Mbps','id_product_family' => '5','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'INETB10Q10','description' => 'High Speed Internet 10GB','id_product_family' => '6','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'INETB10Q30','description' => 'High Speed Internet 30GB','id_product_family' => '6','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('products')->insert(['name' => 'INETB10Q50','description' => 'High Speed Internet 50GB','id_product_family' => '6','price' => '100000','ppn' => '100','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
    }
}
