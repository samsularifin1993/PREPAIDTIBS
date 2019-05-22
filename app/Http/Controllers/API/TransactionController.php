<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class TransactionController extends Controller
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

    public function trxSuccess()
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_trx_success") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                a.transidmerchant AS trans_id_merchant,
                a.item_id AS item_id,
                a.nd AS nd,
                a.duration AS duration,
                a.price AS price,
                a.ppn AS ppn,
                TO_CHAR(a.payment_dtm, 'dd-Mon-yyyy hh24:mi:ss') AS payment_dtm,
                TO_CHAR(a.request_dtm, 'dd-Mon-yyyy hh24:mi:ss') AS request_dtm,
                TO_CHAR(a.start_dtm, 'dd-Mon-yyyy hh24:mi:ss') AS start_dtm,
                TO_CHAR(a.end_dtm, 'dd-Mon-yyyy hh24:mi:ss') AS end_dtm,
                a.datemonth AS datemonth,
                (CASE WHEN a.prov_status = '2' THEN 'Sudah' ELSE 'Belum' END) AS prov_status,
                a.settlement_id AS settlement_id,
                a.transfer_dtm AS transfer_dtm,
                a.settlement_status AS settlement_status,                
                b.name AS channel,
                c.name AS product,
                d.type AS payment,
                e.datel AS org                
            FROM transaction a
            LEFT JOIN channel b
                ON a.channel_code = b.code
            LEFT JOIN product_map c
                ON a.product_code = c.code
            LEFT JOIN payment d
                ON a.payment_code = d.code
            LEFT JOIN org e
                ON a.org_id = e.id
            ORDER BY a.datemonth DESC
         ");

         \App\Log::createWithApi('Show Transaction Success');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function trxRejected()
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_trx_reject") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                transidmerchant AS id,
                transidmerchant AS trans_id_merchant,
                channel AS channel,
                item_id AS item_id,
                nd AS nd,
                duration AS duration,
                price AS price,
                ppn AS ppn,
                product_family AS product_family,
                product AS product,
                payment_type AS payment,
                treg AS treg,
                witel AS witel,
                datel AS datel,
                payment_dtm AS payment_dtm,
                request_dtm AS request_dtm,
                start_dtm AS start_dtm,
                end_dtm AS end_dtm,
                created_dtm AS created_dtm,
                err_code AS error_code,
                err_desc AS error_desc,
                prov_status AS prov_status
            FROM rejected_transaction
            ORDER BY created_dtm ASC
         ");

         \App\Log::createWithApi('Show Transaction Rejected');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function reprocessRejected(Request $request)
    {
        $idExp = explode("|", $request->id);

        $result["error"] = false;
        $result["result"] = 'success';

        \DB::beginTransaction();

        try {
                $data = \DB::connection('tibs')->select("
                        SELECT
                            *
                        FROM rejected_transaction
                        WHERE
                            transidmerchant = ? AND
                            request_dtm = ? AND
                            payment_dtm = ? AND
                            start_dtm = ? AND
                            end_dtm = ? AND
                            created_dtm = ?
                    ", []);
                    
                \DB::statement("
                    INSERT INTO
                        transaction_successes (trans_id_merchant, channel, product, nd, duration, price, ppn, payment_dtm, request_dtm, start_dtm, end_dtm, payment, org, prov_status, created_at, updated_at)
                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                ",[$data[0]->trans_id_merchant, $data[0]->channel, $data[0]->product, $data[0]->nd, $data[0]->duration, $data[0]->price, $data[0]->ppn, $data[0]->payment_dtm, $data[0]->request_dtm, $data[0]->start_dtm, $data[0]->end_dtm, $data[0]->payment, $data[0]->org, $data[0]->prov_status, $data[0]->created_at, date('Y-m-d H:i:s')]);

                \DB::statement("
                    DELETE FROM transaction_rejecteds
                        WHERE id = ?
                ",[$request->id]);

            \DB::commit();

            return json_encode($result);
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        $result["error"] = true;
        $result["result"] = 'Fail';

        \App\Log::create('Retry Process Rejected Transaction to Successfull');

        return json_encode($result);
    }
}
