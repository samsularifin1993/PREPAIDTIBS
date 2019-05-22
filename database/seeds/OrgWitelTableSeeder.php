<?php

use Illuminate\Database\Seeder;

class OrgWitelTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    
    public function run()
    {
        DB::table('org_witels')->insert(['name' => 'NASIONAL','id_regional' => '1','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL ACEH','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BANGKA BELITUNG','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BENGKULU','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAMBI','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL LAMPUNG','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL MEDAN','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL RIAU DARATAN','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL RIAU KEPULAUAN','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SUMATERA BARAT','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SUMATERA SELATAN','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SUMATERA UTARA','id_regional' => '2','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BANTEN','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BEKASI','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BOGOR','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAKARTA BARAT','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAKARTA PUSAT','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAKARTA SELATAN','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAKARTA TIMUR','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JAKARTA UTARA','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL TANGERANG','id_regional' => '3','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BANDUNG','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BANDUNG BARAT','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL CIREBON','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KARAWANG','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SUKABUMI','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL TASIKMALAYA','id_regional' => '4','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL DI YOGYAKARTA','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KUDUS','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL MAGELANG','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL PEKALONGAN','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL PURWOKERTO','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SEMARANG','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SOLO','id_regional' => '5','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL DENPASAR','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL GRESIK DAN MADURA','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL JEMBER','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KEDIRI','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL MADIUN','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL MALANG','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL NUSA TENGGARA BARAT','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL NUSA TENGGARA TIMUR','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL PASURUAN','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SIDOARJO','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SINGARAJA','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SURABAYA','id_regional' => '6','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL BALIKPAPAN','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KALIMANTAN BARAT','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KALIMANTAN SELATAN','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KALIMANTAN TENGAH','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL KALIMANTAN UTARA','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SAMARINDA','id_regional' => '7','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL GORONTALO','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL MALUKU','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL PAPUA','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL PAPUA BARAT','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SULAWESI SELATAN','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SULAWESI SELATAN BARAT','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SULAWESI TENGAH','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SULAWESI TENGGARA','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
        DB::table('org_witels')->insert(['name' => 'WITEL SULAWESI UTARA MALUKU UTARA','id_regional' => '8','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s'),]);
    }
}
