<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }

    public static function create($activity, $datetime = '') {
        if($datetime == ''){
            $datetime = date('Y-m-d H:i:s');
        }

        $ch = curl_init();
        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL => 'http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR'],
            CURLOPT_TIMEOUT => 1,
            CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'],
        );

        curl_setopt_array($ch, $curl_opt);
        $content = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);

        $obj = json_decode($content);

        $data = [
            \Auth::guard('user')->user()->id,
            $activity,
            $_SERVER['REMOTE_ADDR'],
            '-',
            'Local',
            '0,0',
            $datetime,
            $datetime
        ];

        // if($obj->{'status'} == 'success'){
        //     $data = [
        //         \Auth::guard('user')->user()->id,
        //         $activity,
        //         $obj->{'query'},
        //         $obj->{'isp'},
        //         $obj->{'as'}."/".$obj->{'city'}."/".$obj->{'country'}."/".$obj->{'countryCode'},
        //         $obj->{'lat'}.",".$obj->{'lon'},
        //         $datetime,
        //         $datetime
        //     ];
        // }

        \DB::beginTransaction();

        try {
            \DB::statement("
                INSERT INTO
                    logs (id_user, activity, ip, isp, location, longlat, created_at, updated_at)
                    VALUES (?,?,?,?,?,?,?,?)
            ",$data);

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }
    }

    public static function createWithApi($activity, $datetime = '') {
        if($datetime == ''){
            $datetime = date('Y-m-d H:i:s');
        }

        $ch = curl_init();
        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL => 'http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR'],
            CURLOPT_TIMEOUT => 1,
            CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'],
        );

        curl_setopt_array($ch, $curl_opt);
        $content = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);

        $obj = json_decode($content);

        $data = [
            \Auth::guard('api')->user()->id,
            '(API) '.$activity,
            $_SERVER['REMOTE_ADDR'],
            '-',
            'Local',
            '0,0',
            $datetime,
            $datetime
        ];

        // if($obj->{'status'} == 'success'){
        //     $data = [
        //         \Auth::guard('user')->user()->id,
        //         $activity,
        //         $obj->{'query'},
        //         $obj->{'isp'},
        //         $obj->{'as'}."/".$obj->{'city'}."/".$obj->{'country'}."/".$obj->{'countryCode'},
        //         $obj->{'lat'}.",".$obj->{'lon'},
        //         $datetime,
        //         $datetime
        //     ];
        // }

        \DB::beginTransaction();

        try {
            \DB::statement("
                INSERT INTO
                    logs (id_user, activity, ip, isp, location, longlat, created_at, updated_at)
                    VALUES (?,?,?,?,?,?,?,?)
            ",$data);

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }
    }
}
