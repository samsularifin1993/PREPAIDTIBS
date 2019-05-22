<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ProductController extends Controller
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

        if(User::permission(\Auth::guard('api')->user()->id, "product_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                t1.code AS id,
                t1.name AS name,
                t1.description AS description,
                t2.name AS product_family,
                t1.created_at AS created,
                t1.updated_at AS updated
            FROM product t1
            LEFT JOIN product_family t2
                ON t1.family_code = t2.code
            ORDER BY t1.code ASC
         ");

         \App\Log::createWithApi('Show menu Product');

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }

    public function generateCode($family_id){
        $data = \DB::connection('tibs')->select("
            SELECT MAX(code) AS maxcode FROM product WHERE family_code = ?
         ", [$family_id]);

        $family_code = str_replace('F', 'D', $family_id);
        $last = (string)substr($data[0]->maxcode, 4, 2);

        $last = preg_replace('/[^0-9]/', '', $last);
        $last++;

        $auto = $family_code . sprintf("%02s", $last);

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

        if(User::permission(\Auth::guard('api')->user()->id, "product_i") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'product_family' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    INSERT INTO
                        product (code, name, description, family_code, created_at, updated_at)
                        VALUES (?,?,?,?,sysdate,sysdate)
                ",[$this->generateCode($request->product_family), strtoupper($request->name), strtoupper($request->description), $request->product_family]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT MAX(code) AS maxcode FROM product WHERE family_code = ?
            ", [$request->product_family]);

            $data = \DB::connection('tibs')->select("
                SELECT
                    t1.code AS id,
                    t1.name AS name,
                    t1.description AS description,
                    t2.name AS product_family,
                    t1.created_at AS created,
                    t1.updated_at AS updated
                FROM product t1
                LEFT JOIN product_family t2
                    ON t1.family_code = t2.code
                WHERE t1.code = ?
            ", [$data[0]->maxcode]);

            $result["result"] = $data;

            \App\Log::createWithApi('New Product');

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

        if(User::permission(\Auth::guard('api')->user()->id, "product_r") === 'false'){
            return response()->json(null, 404);
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                t1.code AS id,
                t1.name AS name,
                t1.description AS description,
                t2.name AS product_family,
                t1.created_at AS created,
                t1.updated_at AS updated
            FROM product t1
            LEFT JOIN product_family t2
                ON t1.family_code = t2.code
            WHERE t1.code = ?
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

        if(User::permission(\Auth::guard('api')->user()->id, "product_u") === 'false'){
            return response()->json(null, 404);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'product_family' => 'required'
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    UPDATE product
                        SET code=?, name=?, description=?, family_code=?, updated_at=sysdate
                        WHERE code=?
                ",[$this->generateCode($request->product_family), strtoupper($request->name), strtoupper($request->description), $request->product_family, $id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            $data = \DB::connection('tibs')->select("
                SELECT
                    t1.code AS id,
                    t1.name AS name,
                    t1.description AS description,
                    t2.name AS product_family,
                    t1.created_at AS created,
                    t1.updated_at AS updated
                FROM product t1
                LEFT JOIN product_family t2
                    ON t1.family_code = t2.code
                WHERE t1.code = ?
            ", [$id]);

            $result["result"] = $data;

            \App\Log::createWithApi('Update Product');

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

        if(User::permission(\Auth::guard('api')->user()->id, "product_family_d") === 'false'){
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
                    DELETE FROM product
                    WHERE code = ?
                ",[$id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            \App\Log::createWithApi('Remove Product');

            return response()->json($result, 200);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();
  
        return response()->json($result, 200);
    }
}
