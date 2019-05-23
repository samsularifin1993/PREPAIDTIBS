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
                a.transidmerchant AS transidmerchant,
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
                transidmerchant AS transidmerchant,
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

         foreach($data as $d){
            try{
                $d->id = $d->transidmerchant.'||'.$d->payment_dtm.'||'.$d->request_dtm.'||'.$d->start_dtm.'||'.$d->end_dtm.'||'.$d->created_dtm;
                // $d->payment_dtm = ($d->payment_dtm != '' ? $this->toTimeStamp($d->payment_dtm) : '');
                // $d->request_dtm = ($d->request_dtm != '' ? $this->toTimeStamp($d->request_dtm) : '');
                // $d->start_dtm = ($d->start_dtm != '' ? $this->toTimeStamp($d->start_dtm) : '');
                // $d->end_dtm = ($d->end_dtm != '' ? $this->toTimeStamp($d->end_dtm) : '');
                // $d->created_dtm = ($d->created_dtm != '' ? $this->toTimeStamp($d->created_dtm) : '');
            }catch(\Exception $e){
                continue;
            }
        }

         \App\Log::createWithApi('Show Transaction Rejected');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function updateRejected(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_trx_reject") === 'false'){
            return response()->json(null, 404);
        }
        
        $exp = explode('||', $request->id);

        $result["error"] = false;
        $result["result"] = 'success';

        \DB::beginTransaction();

        try {

            for($i=0;$i<6;$i++){
                $d[$i] = ' IS NULL ';
            }

            for($i=0;$i<6;$i++){
                if($exp[$i] != ''){
                    $d[$i] = " = '".$exp[$i]."' ";
                }
            }

            $data = \DB::connection('tibs')->statement("
                    UPDATE rejected_transaction
                    SET
                        transidmerchant = ?,
                        channel = ?,
                        item_id = ?,
                        product_family = ?,
                        product = ?,
                        nd = ?,
                        duration = ?,
                        price = ?,
                        ppn = ?,
                        payment_dtm = ?,
                        request_dtm = ?,
                        start_dtm = ?,
                        end_dtm = ?,
                        treg = ?,
                        witel = ?,
                        datel = ?,
                        payment_type = ?,
                        prov_status = ?,
                        created_dtm = TO_CHAR(systimestamp, 'YYYY-MM-DD HH24:MI:SS')
                    WHERE
                        transidmerchant ".$d[0]." AND
                        payment_dtm ".$d[1]." AND
                        request_dtm ".$d[2]." AND
                        start_dtm ".$d[3]." AND
                        end_dtm ".$d[4]." AND
                        created_dtm ".$d[5]."
                    ", [
                        $request->transidmerchant,
                        $request->channel,
                        $request->item_id,
                        $request->product_family,
                        $request->product,
                        $request->nd,
                        $request->duration,
                        $request->price,
                        $request->ppn,
                        $request->payment_dtm,
                        $request->request_dtm,
                        $request->start_dtm,
                        $request->end_dtm,
                        $request->treg,
                        $request->witel,
                        $request->datel,
                        $request->payment_type,
                        $request->prov_status
                     ]);

            \DB::commit();

            if($data == true){
                $result["error"] = false;
                $result["result"] = 'success';
            }else{
                $result["error"] = true;
                $result["result"] = 'fail';
            }

            \App\Log::create('Edit Rejected Transaction');
 
            return response()->json($result, 200);

        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        $result["error"] = true;
        $result["result"] = 'fail';

        return response()->json($result, 200);
    }

    public function retryRejected(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_trx_reject") === 'false'){
            return response()->json(null, 404);
        }

        $exp = explode('||', $request->id);

        $result["error"] = false;
        $result["result"] = 'success';

        \DB::beginTransaction();

        try {

            for($i=0;$i<6;$i++){
                $d[$i] = ' IS NULL ';
            }

            for($i=0;$i<6;$i++){
                if($exp[$i] != ''){
                    $d[$i] = " = '".$exp[$i]."' ";
                }
            }

            $data = \DB::connection('tibs')->select("
                    SELECT
                        *
                    FROM rejected_transaction
                    WHERE
                        transidmerchant ".$d[0]." AND
                        payment_dtm ".$d[1]." AND
                        request_dtm ".$d[2]." AND
                        start_dtm ".$d[3]." AND
                        end_dtm ".$d[4]." AND
                        created_dtm ".$d[5]."
                    ");
                    
            $conn = oci_connect(env('DB_USERNAME_TIBS', ''), env('DB_PASSWORD_TIBS', ''), env('DB_TNS_TIBS', ''));
            if (!$conn) {
                $e = oci_error();
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            $stid = oci_parse($conn, "BEGIN REPROCESS_REJECT_TRX_ITEM(:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o,:p,:q,:r,:s,:msg); END;");
            oci_bind_by_name($stid,':a', $data[0]->transidmerchant);
            oci_bind_by_name($stid,':b', $data[0]->channel);
            oci_bind_by_name($stid,':c', $data[0]->item_id);
            oci_bind_by_name($stid,':d', $data[0]->product_family);
            oci_bind_by_name($stid,':e', $data[0]->product);
            oci_bind_by_name($stid,':f', $data[0]->nd);
            oci_bind_by_name($stid,':g', $data[0]->duration);
            oci_bind_by_name($stid,':h', $data[0]->price);
            oci_bind_by_name($stid,':i', $data[0]->ppn);
            oci_bind_by_name($stid,':j', $data[0]->request_dtm);
            oci_bind_by_name($stid,':k', $data[0]->payment_dtm);
            oci_bind_by_name($stid,':l', $data[0]->start_dtm);
            oci_bind_by_name($stid,':m', $data[0]->end_dtm);
            oci_bind_by_name($stid,':n', $data[0]->payment_type);
            oci_bind_by_name($stid,':o', $data[0]->treg);
            oci_bind_by_name($stid,':p', $data[0]->witel);
            oci_bind_by_name($stid,':q', $data[0]->datel);
            oci_bind_by_name($stid,':r', $data[0]->created_dtm);
            oci_bind_by_name($stid,':s', $data[0]->prov_status);
            oci_bind_by_name($stid,':msg',$message,40);
            oci_execute($stid);

            \DB::commit();

            \App\Log::createWithApi('Retry Process Rejected Transaction to Successfull');

            return response()->json($result, 200);
        } catch (\Throwable $e) {
            \DB::rollback();
            throw $e;
        }

        $result["error"] = true;
        $result["result"] = 'Fail';
 
        return response()->json($result, 200);
    }

    public function retryBulkRejected(Request $request)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "r_trx_reject") === 'false'){
            return response()->json(null, 404);
        }

        $idExp = explode(",", $request->id);
        
        $result["error"] = false;
        $result["result"] = 'success';

        \DB::beginTransaction();

        $i=0;
        if(count($idExp) > 0){
            foreach($idExp as $id){
                try {
                    $exp = explode('||', $id);

                    for($i=0;$i<6;$i++){
                        $d[$i] = ' IS NULL ';
                    }
        
                    for($i=0;$i<6;$i++){
                        if($exp[$i] != ''){
                            $d[$i] = " = '".$exp[$i]."' ";
                        }
                    }

                    $data = \DB::connection('tibs')->select("
                        SELECT
                        *
                        FROM rejected_transaction
                        WHERE
                            transidmerchant ".$d[0]." AND
                            payment_dtm ".$d[1]." AND
                            request_dtm ".$d[2]." AND
                            start_dtm ".$d[3]." AND
                            end_dtm ".$d[4]." AND
                            created_dtm ".$d[5]."
                        ");
                    
                    $conn = oci_connect(env('DB_USERNAME_TIBS', ''), env('DB_PASSWORD_TIBS', ''), env('DB_TNS_TIBS', ''));
                    if (!$conn) {
                        $e = oci_error();
                        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                    }

                    $stid = oci_parse($conn, "BEGIN REPROCESS_REJECT_TRX_ITEM(:a,:b,:c,:d,:e,:f,:g,:h,:i,:j,:k,:l,:m,:n,:o,:p,:q,:r,:s,:msg); END;");
                    oci_bind_by_name($stid,':a', $data[0]->transidmerchant);
                    oci_bind_by_name($stid,':b', $data[0]->channel);
                    oci_bind_by_name($stid,':c', $data[0]->item_id);
                    oci_bind_by_name($stid,':d', $data[0]->product_family);
                    oci_bind_by_name($stid,':e', $data[0]->product);
                    oci_bind_by_name($stid,':f', $data[0]->nd);
                    oci_bind_by_name($stid,':g', $data[0]->duration);
                    oci_bind_by_name($stid,':h', $data[0]->price);
                    oci_bind_by_name($stid,':i', $data[0]->ppn);
                    oci_bind_by_name($stid,':j', $data[0]->request_dtm);
                    oci_bind_by_name($stid,':k', $data[0]->payment_dtm);
                    oci_bind_by_name($stid,':l', $data[0]->start_dtm);
                    oci_bind_by_name($stid,':m', $data[0]->end_dtm);
                    oci_bind_by_name($stid,':n', $data[0]->payment_type);
                    oci_bind_by_name($stid,':o', $data[0]->treg);
                    oci_bind_by_name($stid,':p', $data[0]->witel);
                    oci_bind_by_name($stid,':q', $data[0]->datel);
                    oci_bind_by_name($stid,':r', $data[0]->created_dtm);
                    oci_bind_by_name($stid,':s', $data[0]->prov_status);
                    oci_bind_by_name($stid,':msg',$message,40);
                    oci_execute($stid);

                    $i++;

                    \DB::commit();
                } catch (\Throwable $e) {
                    \DB::rollback();
                    throw $e;
                }
            }

            if($i = count($idExp)){
                \App\Log::createWithApi('Retry Process Rejected Transaction to Successfull');

                return response()->json($result, 200);
            }
        }

        $result["error"] = true;
        $result["result"] = 'Fail';
 
        return response()->json($result, 200);
    }
}
