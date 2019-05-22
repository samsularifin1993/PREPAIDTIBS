<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ReportController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function summary()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "r_revenue") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
                SELECT
                    COUNT(a.transidmerchant) AS trans_count,
                    SUM(a.price) AS price,
                    SUM(a.ppn) AS ppn
                FROM transaction a
                LEFT JOIN org b ON a.org_id = b.id
         ");

         \App\Log::create('Show Revenue Summary Report');

        return view('report_revenue_summary', ['trans_count'=>$data[0]->trans_count, 'revenue'=>$data[0]->price, 'ppn'=>$data[0]->ppn]);
    }

    public function getSummaryRevenue(Request $request)
    {
        $month = '';

        if($request->month != null){
            $month = "EXTRACT(month FROM a.payment_dtm) = '$request->month' AND ";
        }

        $result['trans_count'] = 0;
        $result['price'] = 0;
        $result['ppn'] = 0;

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
            $result['trans_count'] = $data[0]->trans_count;
            $result['price'] = $data[0]->price;
            $result['ppn'] = $data[0]->ppn;
         }

         return json_encode($result);
    }

    public function byProduct()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "r_revenue") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                COUNT(a.transidmerchant) AS trans_count,
                SUM(a.price) AS price,
                SUM(a.ppn) AS ppn
            FROM transaction a
            LEFT JOIN org b ON a.org_id = b.id
         ");

         \App\Log::create('Show Revenue By Product Report');

        return view('report_revenue_by_product', ['trans_count'=>$data[0]->trans_count, 'revenue'=>$data[0]->price, 'ppn'=>$data[0]->ppn]);
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

    public function getAllSummary(Request $request){
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
         
         return \DataTables::of($data)->make(true);
    }

    public function getAllByProduct(Request $request){
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

        return \DataTables::of($data)->make(true);
    }
}
