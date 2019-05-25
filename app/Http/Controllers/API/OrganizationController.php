<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class OrganizationController extends Controller
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

        if(User::permission(\Auth::guard('api')->user()->id, "organization_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                datel_code AS id,
                datel AS datel,   
                witel_code AS witel_code,   
                witel AS witel,
                reg_code AS reg_code,
                regional AS regional,
                TO_CHAR(created_at, 'dd-Mon-yyyy hh24:mi:ss') AS created,
                TO_CHAR(updated_at, 'dd-Mon-yyyy hh24:mi:ss') AS updated
            FROM org
         ");

         \App\Log::createWithApi('Show menu Organization');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function generateCode($witel_id){
        $data = \DB::connection('tibs')->select("
            SELECT MAX(datel_code) AS maxcode FROM org WHERE witel_code = ?
         ", [$witel_id]);

        $witel_code = (string)substr($data[0]->maxcode, 0, 3);
        $last = (string)substr($data[0]->maxcode, 3, 3);

        $last = preg_replace('/[^0-9]/', '', $last);
        $last++;

        $char = $witel_code.'D';
        $auto = $char . sprintf("%02s", $last);

        return $auto;
    }

    public function generateId(){
        $data = \DB::connection('tibs')->select("
            SELECT MAX(id) AS maxcode FROM org
         ");

        $last = preg_replace('/[^0-9]/', '', $data[0]->maxcode);
        $last++;

        return $last;
    }

    public function getRegional($witel_id){
        $data = \DB::connection('tibs')->select("
            SELECT DISTINCT regional, reg_code FROM org WHERE witel_code = ?
         ",[$witel_id]);

        return $data;
    }

    public function getWitel($witel_id){
        $data = \DB::connection('tibs')->select("
            SELECT DISTINCT witel, witel_code FROM org WHERE witel_code = ?
         ",[$witel_id]);

        return $data;
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

        if(User::permission(\Auth::guard('api')->user()->id, "organization_i") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'regional' => 'required',
            'witel' => 'required',
            'datel' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            $regional = $this->getRegional($request->witel);
            $witel = $this->getWitel($request->witel);

            \DB::beginTransaction();

            $code = $this->generateId();

            try {
                \DB::connection('tibs')->statement("
                    INSERT INTO
                        org (id, regional, reg_code, witel, witel_code, datel, datel_code, created_at, updated_at)
                        VALUES (?,?,?,?,?,?,?,sysdate,sysdate)
                ",[$code, strtoupper($regional[0]->regional), $regional[0]->reg_code, strtoupper($witel[0]->witel), $witel[0]->witel_code, strtoupper($request->datel), $this->generateCode($witel[0]->witel_code)]);

                \DB::connection('tibs')->statement("
                    INSERT INTO
                        org_map (name, type, code)
                        VALUES (?,?,?)
                ",[strtoupper($request->datel), '3', $this->generateCode($witel[0]->witel_code)]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT MAX(id) AS maxcode FROM org
            ");

            $data = \DB::connection('tibs')->select("
                SELECT
                    datel_code AS id,
                    datel AS datel,   
                    witel_code AS witel,
                    reg_code AS regional
                FROM org
                WHERE id = ?
            ", [$data[0]->maxcode]);

            $result["result"] = $data;

            \App\Log::createWithApi('New Organization');

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

        if(User::permission(\Auth::guard('api')->user()->id, "organization_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                datel_code AS id,
                datel AS datel,   
                witel_code AS witel,
                reg_code AS regional
            FROM org
            WHERE datel_code = ?
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

        if(User::permission(\Auth::guard('api')->user()->id, "organization_u") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'datel' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    UPDATE org
                        SET datel=?, updated_at=sysdate
                        WHERE datel_code=?
                ",[strtoupper($request->datel), $id]);

                \DB::connection('tibs')->statement("
                    UPDATE org_map
                        SET name=?
                        WHERE code=?
                ",[strtoupper($request->datel), $id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT
                    datel_code AS id,
                    datel AS datel,
                    witel_code AS witel,
                    reg_code AS regional
                FROM org
                WHERE datel_code = ?
            ", [$id]);

            $result["result"] = $data;

            \App\Log::createWithApi('Update Organization');

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

        if(User::permission(\Auth::guard('api')->user()->id, "organization_d") === 'false'){
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
                    DELETE FROM org
                    WHERE datel_code = ?
                ",[$id]);

                \DB::connection('tibs')->statement("
                    DELETE FROM org_map
                    WHERE code = ?
                ",[$id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            \App\Log::createWithApi('Remove Organization');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();
  
        return response()->json($result, 200);
    }
}
