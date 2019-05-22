<?php

use Illuminate\Database\Seeder;

class ChannelsTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('channels')->insert([
            'name' => 'MYINDIHOME',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'MYTELKOM',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'NPP_DBS',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'NPP_EBIS',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'NPP_DCS',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'WEBIH',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'HOOBEX',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'EPG',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'GAMESHUB',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'HSIPREP',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'DOKU',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('channels')->insert([
            'name' => 'KENDEDES',
            'description' => '-',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
