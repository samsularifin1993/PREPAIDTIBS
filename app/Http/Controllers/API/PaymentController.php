<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class PaymentController extends Controller
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
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "payment_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                type AS type,
                description AS description,
                created_at AS created,
                updated_at AS updated
            FROM payment
         ");

         \App\Log::createWithApi('Show menu Payment');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function generateCode(){
        $data = \DB::connection('tibs')->select("
            SELECT MAX(code) AS maxcode FROM payment
         ");

        $last = preg_replace('/[^0-9]/', '', $data[0]->maxcode);
        $last++;

        $char = 'PT';
        $auto = $char . sprintf("%02s", $last);

        return $auto;
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
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "payment_i") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'type' => 'required',
            'description' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    INSERT INTO
                        payment (code, type, description, created_at, updated_at)
                        VALUES (?,?,?, sysdate, sysdate)
                ",[$this->generateCode(), strtoupper($request->type), strtoupper($request->description)]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT MAX(code) AS maxcode FROM payment
            ");

            $data = \DB::connection('tibs')->select("
                SELECT
                    code AS id,
                    type AS type,
                    description AS description,
                    created_at AS created,
                    updated_at AS updated
                FROM payment
                WHERE code = ?
            ", [$data[0]->maxcode]);

            $result["result"] = $data;

            \App\Log::createWithApi('New Payment');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "payment_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                type AS type,
                description AS description,
                created_at AS created,
                updated_at AS updated
            FROM payment WHERE code = ?
         ", [$id]);

         $result["error"] = false;
         $result["result"] = $data;
  
         return response()->json($result, 200);
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
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "payment_u") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'type' => 'required',
            'description' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    UPDATE payment
                        SET type=?, description=?, updated_at=sysdate
                        WHERE code=?
                ",[strtoupper($request->type), strtoupper($request->description), $id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT
                    code AS id,
                    type AS type,
                    description AS description,
                    created_at AS created,
                    updated_at AS updated
                FROM payment WHERE code = ?
            ", [$id]);

            $result["result"] = $data;

            \App\Log::createWithApi('Update Payment');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();
  
        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        if(User::permission(\Auth::guard('api')->user()->id, "payment_d") === 'false'){
            return response()->json(null, 404);
        }

        // $validator = \Validator::make($request->all(), [
        //     'id' => 'required',
        // ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($id != '') {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    DELETE FROM payment
                    WHERE code = ?
                ",[$id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            \App\Log::createWithApi('Remove Payment');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();
  
        return response()->json($result, 200);
    }
}
