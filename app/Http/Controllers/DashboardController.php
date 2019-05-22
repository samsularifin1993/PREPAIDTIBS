<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DashboardController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function administrator()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "v_dashboard_admin") === 'false'){
            return redirect()->route('user.login');
        }

        $list = null;

        $data = \DB::select("
                SELECT COUNT(*) AS count FROM users
         ");

        $list['user'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(*) AS count FROM channel
         ");

        $list['channel'] = $data[0]->count;
        
        $data = \DB::connection('tibs')->select("
                SELECT COUNT(*) AS count FROM payment
         ");

        $list['payment'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(*) AS count FROM product_family
         ");

        $list['product_family'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(*) AS count FROM product
         ");

        $list['product'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(DISTINCT reg_code) AS count FROM org
         ");

        $list['regional'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(DISTINCT witel_code) AS count FROM org
         ");

        $list['witel'] = $data[0]->count;

        $data = \DB::connection('tibs')->select("
                SELECT COUNT(DISTINCT datel_code) AS count FROM org
         ");

        $list['datel'] = $data[0]->count;

        \App\Log::create('Show menu Dashboard administator');

        return view('dashboard_administrator', ['list' => $list]);
    }

    public function finance()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "v_dashboard_revenue") === 'false'){
            return redirect()->route('user.login');
        }

        \App\Log::create('Show menu Dashboard finance');

        return view('dashboard_finance');
    }

    public function pivot()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "v_dashboard_revenue") === 'false'){
            return redirect()->route('user.login');
        }

        \App\Log::create('Show menu Dashboard finance');

        return view('dashboard_pivot');
    }

    public function pivot2()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "v_dashboard_revenue") === 'false'){
            return redirect()->route('user.login');
        }

        \App\Log::create('Show menu Dashboard finance');

        return view('dashboard_pivot2');
    }

    public function pivotData()
    {
        return view('pivot_data');
    }

    public function value(Request $request)
    {
        $data = \DB::connection('tibs')->select("
            SELECT SUM(price) AS sum_price, datemonth
            FROM transaction
            WHERE EXTRACT(year FROM payment_dtm) = '$request->year'
            GROUP BY EXTRACT(month FROM payment_dtm), datemonth
            ORDER BY EXTRACT(month FROM payment_dtm) ASC
         ");

         $i = 0;
        foreach($data as $d){
            $data[$i]->datemonth = (int)substr($d->datemonth, 4, 2);
            $i++;
        }

        return json_encode($data);
    }

    public function product(Request $request)
    {
        $data = \DB::connection('tibs')->select("
                SELECT b.name AS Product, COUNT(a.transidmerchant) AS count
                FROM transaction a
                LEFT JOIN product_map b ON a.product_code = b.code
                WHERE EXTRACT(year FROM a.payment_dtm) = '$request->year'
                GROUP BY b.name
                ORDER BY count DESC
         ");

        return json_encode($data);
    }

    public function productAll(Request $request)
    {
        $data = \DB::connection('tibs')->select("
                SELECT b.name AS Product, SUM(a.price) AS Price
                FROM transaction a
                LEFT JOIN product_map b ON a.product_code = b.code
                WHERE EXTRACT(year FROM a.payment_dtm) = '$request->year'
                GROUP BY b.name
         ");

        return json_encode($data);
    }

    public function productAll2(Request $request)
    {
        $data = \DB::connection('tibs')->select("
            SELECT
                b.name AS Product,
                b.family AS Product_family,
                c.regional AS Regional,
                c.witel AS Witel, 
                c.datel AS Datel, 
                d.name AS Payment, 
                a.price AS Price,
                a.ppn AS PPN
            FROM transaction a
            LEFT JOIN product_map b ON a.product_code = b.code
            LEFT JOIN org c ON a.org_id = c.id
            LEFT JOIN payment_map d ON a.payment_code = d.code
            WHERE EXTRACT(year FROM a.payment_dtm) = '$request->year'
         ");

        return json_encode($data);
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
}
