<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function transactionSuccess()
    {
        /*if(\Auth::guard('user')->check() && \Auth::guard('user')->user()->role != 'finance'){
            return redirect()->route('dashboard.finance');
        }*/

        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "r_trx_success") === 'false'){
            return redirect()->route('user.login');
        }

        \App\Log::create('Show Transaction Success');

        return view('transaction_success');
    }

    public function transactionRejected()
    {
        /*if(\Auth::guard('user')->check() && \Auth::guard('user')->user()->role != 'finance'){
            return redirect()->route('dashboard.finance');
        }*/

        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "r_trx_reject") === 'false'){
            return redirect()->route('user.login');
        }

        \App\Log::create('Show Transaction Rejected');

        return view('transaction_rejected');
    }
    
    public function retryRejected(Request $request)
    {
        $exp = explode('||', $request->id);

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
                        payment_dtm = ? AND
                        request_dtm = ? AND
                        start_dtm = ? AND
                        end_dtm = ? AND
                        created_dtm = ?
                    ", $exp);
                    
            \DB::statement("
                INSERT INTO transaction
                    (
                        transidmerchant,
                        channel_code,
                        item_id,
                        product_code,
                        nd,
                        duration,
                        price,
                        ppn,
                        request_dtm,
                        payment_dtm,
                        start_dtm,
                        end_dtm,
                        payment_code,
                        org_id,
                        datemonth,
                        prov_status,
                        settlement_status
                    )
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
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

    public function retryBulkRejected(Request $request)
    {
        $idExp = explode(",", $request->id);
        
        $result["error"] = false;
        $result["result"] = 'success';

        \DB::beginTransaction();

        $i=0;
        if(count($idExp) > 0){
            foreach($idExp as $id){
                try {
                    $data = \DB::select("
                                SELECT
                                    *
                                FROM transaction_rejecteds
                                WHERE id = '$id'
                                ORDER BY created_at ASC
                            ");
                            
                        \DB::statement("
                            INSERT INTO
                                transaction_successes (trans_id_merchant, channel, product, nd, duration, price, ppn, payment_dtm, request_dtm, start_dtm, end_dtm, payment, org, prov_status, created_at, updated_at)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                        ",[$data[0]->trans_id_merchant, $data[0]->channel, $data[0]->product, $data[0]->nd, $data[0]->duration, $data[0]->price, $data[0]->ppn, $data[0]->payment_dtm, $data[0]->request_dtm, $data[0]->start_dtm, $data[0]->end_dtm, $data[0]->payment, $data[0]->org, $data[0]->prov_status, $data[0]->created_at, date('Y-m-d H:i:s')]);

                        \DB::statement("
                            DELETE FROM transaction_rejecteds
                                WHERE id = ?
                        ",[$id]);

                    $i++;

                    \DB::commit();
                } catch (\Throwable $e) {
                    \DB::rollback();
                    throw $e;
                }
            }

            if($i = count($idExp)){
                \App\Log::create('Retry Process Rejected Transaction to Successfull');
                return json_encode($result);
            }
        }

        $result["error"] = true;
        $result["result"] = 'Fail';

        return json_encode($result);
    }

    public function getAllSuccess(Request $request){
        // if($request->order_by == 'channel'){
        //     $request->order_by = 'a.channel';
        // }else{
        //     $request->order_by = 'a.created_at';
        // }

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

        return \DataTables::of($data)->make(true);
    }

    public function getAllRejected(){
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

        foreach($data as $d){
            try{
                $d->id = $d->trans_id_merchant.'||'.$d->payment_dtm.'||'.$d->request_dtm.'||'.$d->start_dtm.'||'.$d->end_dtm.'||'.$d->created_dtm;
                $d->payment_dtm = $this->toTimeStamp($d->payment_dtm);
                $d->request_dtm = $this->toTimeStamp($d->request_dtm);
                $d->start_dtm = $this->toTimeStamp($d->start_dtm);
                $d->end_dtm = $this->toTimeStamp($d->end_dtm);
                $d->created_dtm = $this->toTimeStamp($d->created_dtm);    
            }catch(\Exception $e){
                continue;
            }
        }

        return \DataTables::of($data)->make(true);
    }

    public function toTimeStamp($datetime){
        $date = new \DateTime($datetime);
        return $date->format('Y-m-d H:i:s');
    }
}
