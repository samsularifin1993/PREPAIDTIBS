<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ApiController extends Controller
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        // if(User::permission(\Auth::guard('user')->user()->id, "source_script") === 'false'){
        //     return redirect()->route('user.login');
        // }

        // \App\Log::create('Show menu Source Script');

        return view('api');
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

    public function getToken()
    {
        $data = \DB::select("
                SELECT token_api FROM users WHERE id = ?
            ",[\Auth::guard('user')->user()->id]);

        $result['token'] = $data[0]->token_api;

        return json_encode($result);
    }

    public function generateToken()
    {
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        \DB::beginTransaction();

        try {
            \DB::statement("
                    UPDATE users
                    SET token_api=?,updated_at=?
                    WHERE id=?
                ",[$token, date('Y-m-d H:i:s'), \Auth::guard('user')->user()->id]);

            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        $result['result'] = true;
        $result['token'] = $token;

        return json_encode($result);
    }

    public function trans_success(Request $request)
    {
        $data = [];

        if($request->token == null){
            return json_encode($data);
        }else if($request->token != null){
            $q = \DB::select("
                SELECT token_api FROM users WHERE token_api = ?
            ",[$request->token]);

            if($q == null){
                return json_encode($data);
            }
        }

        $month = '';

        if($request->month != ''){
            $month = "MONTH(a.payment_dtm) = '$request->month' AND ";
        }

        $data = \DB::select("
                SELECT
                    c.name AS 'Product Family',
                    b.name AS 'Product',
                    f.name AS 'Regional', 
                    e.name AS 'Witel', 
                    d.name AS 'Datel', 
                    g.type AS 'Payment', 
                    a.price AS 'Price'
                FROM transaction_successes AS a
                LEFT JOIN products AS b ON a.product = b.id
                LEFT JOIN product_families AS c ON b.id_product_family = c.id
                LEFT JOIN org_datels AS d ON a.org = d.id
                LEFT JOIN org_witels AS e ON d.id_witel = e.id
                LEFT JOIN org_regionals AS f ON e.id_regional = f.id
                LEFT JOIN payments AS g ON a.payment = g.id
                WHERE 
                    ".$month."
                    YEAR(a.payment_dtm) = '$request->year'
         ");

        return json_encode($data);
    }

    public function byOrg(Request $request){
        $data = [];

        if($request->token == null){
            return json_encode($data);
        }else if($request->token != null){
            return json_encode($data);
        }

        $month = '';

        if($request->month != ''){
            $month = "MONTH(a.payment_dtm) = '$request->month' AND ";
        }

        $data = \DB::select("
            SELECT
                d.name AS treg,
                c.name AS witel,
                b.name AS datel,
                COUNT(a.id) AS trans_count,
                SUM(a.price) AS price,
                SUM(a.ppn) AS ppn
            FROM transaction_successes AS a
            LEFT JOIN org_datels AS b ON a.org = b.id
            LEFT JOIN org_witels AS c ON b.id_witel = c.id
            LEFT JOIN org_regionals AS d ON c.id_regional = d.id
            WHERE
                ".$month."
                YEAR(a.payment_dtm) = '$request->year'
            GROUP BY a.org
            ORDER BY a.payment_dtm ASC
         ");

        return json_encode($data);
    }

    public function byProduct(Request $request){
        $data = [];

        if($request->token == null){
            return json_encode($data);
        }else if($request->token != null){
            return json_encode($data);
        }

        $month = '';

        if($request->month != ''){
            $month = "MONTH(a.payment_dtm) = '$request->month' AND ";
        }
        
        $data = \DB::select("
            SELECT
                c.name AS product_family,
                b.name AS product,
                COUNT(a.id) AS trans_count,
                SUM(a.price) AS price,
                SUM(a.ppn) AS ppn
            FROM transaction_successes AS a
            LEFT JOIN products AS b ON a.product = b.id
            LEFT JOIN product_families AS c ON b.id_product_family = c.id
            WHERE
                ".$month."
                YEAR(a.payment_dtm) = '$request->year'
            GROUP BY a.product
         ");

         return json_encode($data);
    }

    public function gettest(){
        $data = \DB::select("
            SELECT
                *
            FROM payments
            WHERE
                type = ''
            ");

        return json_encode($data);
    }

    public function indexChannel(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "channel_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                name AS name,
                description AS description,
                TO_CHAR(created_at, 'dd-Mon-yyyy hh24:mi:ss') AS created,
                TO_CHAR(updated_at, 'dd-Mon-yyyy hh24:mi:ss') AS updated
            FROM channel
         ");

         \App\Log::createWithApi('Show menu Channel'.$request->ip());

        $result['error'] = false;
        $result['data'] = $data;
 
        return response()->json($result, 200);
    }
}
