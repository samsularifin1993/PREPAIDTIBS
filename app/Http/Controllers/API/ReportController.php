<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sumAll(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_revenue") === 'false'){
            return response()->json(null, 404);
        }

        $month = '';

        if($request->month != null){
            $month = "EXTRACT(month FROM a.payment_dtm) = '$request->month' AND ";
        }

        $d['trans_count'] = 0;
        $d['price'] = 0;
        $d['ppn'] = 0;

        $data = \DB::connection('tibs')->select("
                SELECT
                    COUNT(a.transidmerchant) AS trans_count,
                    SUM(a.price) AS price,
                    SUM(a.ppn) AS ppn
                FROM transaction a
                LEFT JOIN org b ON a.org_id = b.id
                WHERE ".$month."EXTRACT(year FROM a.payment_dtm) = '$request->year'
         ");

         if($data[0]->trans_count != 0){
            $d['trans_count'] = $data[0]->trans_count;
            $d['price'] = $data[0]->price;
            $d['ppn'] = $data[0]->ppn;
         }

        $result['error'] = false;
        $result['result'] = $d;
 
        return response()->json($result, 200);
    }

    public function sumByOrg(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_revenue") === 'false'){
            return response()->json(null, 404);
        }

        $month = '';

        if($request->month != ''){
            $month = "EXTRACT(month FROM a.payment_dtm) = '$request->month' AND ";
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                b.regional AS treg,
                b.witel AS witel,
                b.datel AS datel,
                COUNT(a.transidmerchant) AS trans_count,
                SUM(a.price) AS price,
                SUM(a.ppn) AS ppn
            FROM transaction a
            LEFT JOIN org b ON a.org_id = b.id
            WHERE 
                ".$month."
                EXTRACT(year FROM a.payment_dtm) = '$request->year'
            GROUP BY b.regional, b.witel, b.datel
         ");

        $result['error'] = false;
        $result['result'] = $d;
 
        return response()->json($result, 200);
    }

    public function sumByProduct(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_revenue") === 'false'){
            return response()->json(null, 404);
        }

        $month = '';

        if($request->month != ''){
            $month = "EXTRACT(month FROM a.payment_dtm) = '$request->month' AND ";
        }
        
        $data = \DB::connection('tibs')->select("
            SELECT
                b.name AS product,
                COUNT(a.transidmerchant) AS trans_count,
                SUM(a.price) AS price,
                SUM(a.ppn) AS ppn
            FROM transaction a
            LEFT JOIN product_map b ON a.product_code = b.code
            WHERE
                ".$month."
                EXTRACT(year FROM a.payment_dtm) = '$request->year'
            GROUP BY b.name
         ");

        $result['error'] = false;
        $result['result'] = $d;
 
        return response()->json($result, 200);
    }
}
