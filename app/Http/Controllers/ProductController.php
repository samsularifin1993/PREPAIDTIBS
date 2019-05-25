<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_r") === 'false'){
            return redirect()->route('user.login');
        }

        $permission['r'] = User::permission(\Auth::guard('user')->user()->id, "product_r");
        $permission['i'] = User::permission(\Auth::guard('user')->user()->id, "product_i");
        $permission['u'] = User::permission(\Auth::guard('user')->user()->id, "product_u");
        $permission['d'] = User::permission(\Auth::guard('user')->user()->id, "product_d");

        \App\Log::create('Show menu Product');

        return view('product', ['permission'=>$permission]);
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

    // public function generateCode(){
    //     $data = \DB::connection('tibs')->select("
    //         SELECT MAX(code) AS maxcode FROM product
    //      ");

    //     $last = preg_replace('/[^0-9]/', '', $data[0]->maxcode);
    //     $last++;

    //     $char = 'PF';
    //     $auto = $char . sprintf("%02s", $last);

    //     return $auto;
    // }

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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_i") === 'false'){
            return redirect()->route('user.login');
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

            $code = $this->generateCode($request->product_family);

            try {
                $data = \DB::connection('tibs')->select("
                    SELECT
                        name
                    FROM product_family
                    WHERE code = ?
                ", [$request->product_family]);

                \DB::connection('tibs')->statement("
                    INSERT INTO
                        product (code, name, description, family_code, created_at, updated_at)
                        VALUES (?,?,?,?,sysdate,sysdate)
                ",[$code, strtoupper($request->name), strtoupper($request->description), $request->product_family]);

                \DB::connection('tibs')->statement("
                    INSERT INTO
                        product_map (name, family, type, code)
                        VALUES (?,?,?,?)
                ",[strtoupper($request->name), $data[0]->name, '2', $code]);


                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('New Product');

        return json_encode($result);
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
    public function edit(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_u") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                t1.code AS id,
                t1.name AS name,
                t1.description AS description,
                t1.family_code AS product_family
            FROM product t1
            LEFT JOIN product_family t2
                ON t1.family_code = t2.code
            WHERE t1.code = ?
         ", [$request->id]);

        return json_encode($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_u") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'name_old' => 'required',
            'description_old' => 'required',
            'product_family_old' => 'required'
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {
            \DB::beginTransaction();

            $code = $this->generateCode($request->product_family_old);

            try {
                $data = \DB::connection('tibs')->select("
                    SELECT
                        name
                    FROM product_family
                    WHERE code = ?
                ", [$request->product_family_old]);

                \DB::connection('tibs')->statement("
                    UPDATE product
                        SET code=?, name=?, description=?, family_code=?, updated_at=sysdate
                        WHERE code=?
                ",[$code, strtoupper($request->name_old), strtoupper($request->description_old), $request->product_family_old, $request->id]);

                \DB::connection('tibs')->statement("
                    UPDATE product_map
                        SET name=?, family=?, code=?
                        WHERE code=?
                ",[strtoupper($request->name_old), $data[0]->name, $code, $request->id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Update Product');

        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_d") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::connection('tibs')->statement("
                    DELETE FROM product
                        WHERE code = ?
                ",[$request->id]);

                \DB::connection('tibs')->statement("
                    DELETE FROM product_map
                        WHERE code = ?
                ",[$request->id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Remove Product');

        return json_encode($result);
    }

    public function getAll(){
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "product_r") === 'false'){
            return redirect()->route('user.login');
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

        return \DataTables::of($data)->make(true);
    }

    public function item(){
        $data = \DB::connection('tibs')->select("
            SELECT
                t1.code AS id,
                t1.name AS name,
                t1.description AS description,
                t2.name AS product_family
            FROM product t1
            LEFT JOIN product_family t2
                ON t1.family_code = t2.code
            ORDER BY t1.code ASC
            ");

        return json_encode($data);
    }
}
