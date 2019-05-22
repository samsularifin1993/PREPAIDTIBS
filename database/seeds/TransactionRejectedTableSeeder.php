<?php

use Illuminate\Database\Seeder;

class TransactionRejectedTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('transaction_rejecteds')->insert([
            'trans_id_merchant' => 'HSIL2018112109384836',
            'channel' => '10',
            'product' => '43',
            'nd' => '122604228220',
            'duration' => '1',
            'price' => '115000',
            'ppn' => '11500',
            'payment_dtm' => date('Y-m-d H:i:s'),
            'request_dtm' => date('Y-m-d H:i:s'),
            'start_dtm' => date('Y-m-d H:i:s'),
            'end_dtm' => date('Y-m-d H:i:s'),
            'payment' => '1',
            'org' => '1',
            'prov_status' => '',
            'error' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('transaction_rejecteds')->insert([
            'trans_id_merchant' => 'HSIL2018112109336736',
            'channel' => '10',
            'product' => '43',
            'nd' => '14123532523',
            'duration' => '1',
            'price' => '117000',
            'ppn' => '11700',
            'payment_dtm' => date('Y-m-d H:i:s'),
            'request_dtm' => date('Y-m-d H:i:s'),
            'start_dtm' => date('Y-m-d H:i:s'),
            'end_dtm' => date('Y-m-d H:i:s'),
            'payment' => '3',
            'org' => '6',
            'prov_status' => '',
            'error' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('transaction_rejecteds')->insert([
            'trans_id_merchant' => 'HSIL2018112109352729',
            'channel' => '10',
            'product' => '43',
            'nd' => '136436634634',
            'duration' => '1',
            'price' => '127000',
            'ppn' => '12700',
            'payment_dtm' => date('Y-m-d H:i:s'),
            'request_dtm' => date('Y-m-d H:i:s'),
            'start_dtm' => date('Y-m-d H:i:s'),
            'end_dtm' => date('Y-m-d H:i:s'),
            'payment' => '3',
            'org' => '6',
            'prov_status' => '',
            'error' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('transaction_rejecteds')->insert([
            'trans_id_merchant' => 'HSIL2018112577752729',
            'channel' => '10',
            'product' => '43',
            'nd' => '147836634634',
            'duration' => '1',
            'price' => '140000',
            'ppn' => '14000',
            'payment_dtm' => date('Y-m-d H:i:s'),
            'request_dtm' => date('Y-m-d H:i:s'),
            'start_dtm' => date('Y-m-d H:i:s'),
            'end_dtm' => date('Y-m-d H:i:s'),
            'payment' => '3',
            'org' => '6',
            'prov_status' => '',
            'error' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('transaction_rejecteds')->insert([
            'trans_id_merchant' => 'HSIL2018123787752729',
            'channel' => '10',
            'product' => '43',
            'nd' => '148366634634',
            'duration' => '1',
            'price' => '140000',
            'ppn' => '14000',
            'payment_dtm' => date('Y-m-d H:i:s'),
            'request_dtm' => date('Y-m-d H:i:s'),
            'start_dtm' => date('Y-m-d H:i:s'),
            'end_dtm' => date('Y-m-d H:i:s'),
            'payment' => '3',
            'org' => '6',
            'prov_status' => '',
            'error' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
