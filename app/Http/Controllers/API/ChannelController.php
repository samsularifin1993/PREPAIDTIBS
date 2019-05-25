<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ChannelController extends Controller
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

         \App\Log::createWithApi('Show menu Channel');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function generateCode(){
        $data = \DB::connection('tibs')->select("
            SELECT MAX(code) AS maxcode FROM channel
         ");

        $last = preg_replace('/[^0-9]/', '', $data[0]->maxcode);
        $last++;

        $char = 'C';
        $auto = $char . sprintf("%03s", $last);

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

        if(User::permission(\Auth::guard('api')->user()->id, "channel_i") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            $code = $this->generateCode();

            try {
                \DB::connection('tibs')->statement("
                    INSERT INTO
                        channel (code, name, description, created_at, updated_at)
                        VALUES (?,?,?, sysdate, sysdate)
                ",[$code, strtoupper($request->name), strtoupper($request->description)]);

                \DB::connection('tibs')->statement("
                    INSERT INTO
                        channel_map (name, code)
                        VALUES (?,?)
                ",[strtoupper($request->name), $code]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT MAX(code) AS maxcode FROM channel
            ");

            $data = \DB::connection('tibs')->select("
                SELECT
                    code AS id,
                    name AS name,
                    description AS description,
                    created_at AS created_at,
                    updated_at AS updated_at
                FROM channel
                WHERE code = ?
            ", [$data[0]->maxcode]);

            $result["result"] = $data;

            \App\Log::createWithApi('New Channel');

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

        if(User::permission(\Auth::guard('api')->user()->id, "channel_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                name AS name,
                description AS description,
                created_at AS created_at,
                updated_at AS updated_at
            FROM channel
            WHERE code = ?
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

        if(User::permission(\Auth::guard('api')->user()->id, "channel_u") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    UPDATE channel
                        SET name=?, description=?, updated_at=sysdate
                        WHERE code=?
                ",[strtoupper($request->name), strtoupper($request->description), $id]);

                \DB::connection('tibs')->statement("
                    UPDATE channel_map
                        SET name=?
                        WHERE code=?
                ",[strtoupper($request->name), $id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT
                    code AS id,
                    name AS name,
                    description AS description,
                    created_at AS created_at,
                    updated_at AS updated_at
                FROM channel
                WHERE code = ?
            ", [$id]);

            $result["result"] = $data;

            \App\Log::createWithApi('Update Channel');

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

        if(User::permission(\Auth::guard('api')->user()->id, "channel_d") === 'false'){
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
                    DELETE FROM channel
                        WHERE code = ?
                ",[$id]);

                \DB::connection('tibs')->statement("
                    DELETE FROM channel_map
                        WHERE code = ?
                ",[$id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            \App\Log::createWithApi('Remove Channel');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();
  
        return response()->json($result, 200);
    }
}
