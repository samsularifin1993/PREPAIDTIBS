<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "payment_r") === 'false'){
            return redirect()->route('user.login');
        }

        $permission['r'] = User::permission(\Auth::guard('user')->user()->id, "payment_r");
        $permission['i'] = User::permission(\Auth::guard('user')->user()->id, "payment_i");
        $permission['u'] = User::permission(\Auth::guard('user')->user()->id, "payment_u");
        $permission['d'] = User::permission(\Auth::guard('user')->user()->id, "payment_d");

        \App\Log::create('Show menu Payment');

        return view('payment', ['permission'=>$permission]);
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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "payment_i") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'type' => 'required',
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
                        payment (code, type, description, created_at, updated_at)
                        VALUES (?,?,?, sysdate, sysdate)
                ",[$code, strtoupper($request->type), strtoupper($request->description)]);

                \DB::connection('tibs')->statement("
                    INSERT INTO
                        payment_map (name, code)
                        VALUES (?,?,?, sysdate, sysdate)
                ",[strtoupper($request->type), $code]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('New Payment');

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

        if(User::permission(\Auth::guard('user')->user()->id, "payment_u") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                type AS type,
                description AS description
            FROM payment WHERE code = ?
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

        if(User::permission(\Auth::guard('user')->user()->id, "payment_u") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'type_old' => 'required',
            'description_old' => 'required',
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
                ",[strtoupper($request->type_old), strtoupper($request->description_old), $request->id]);

                \DB::connection('tibs')->statement("
                    UPDATE payment_map
                        SET name=?
                        WHERE code=?
                ",[strtoupper($request->type_old), $request->id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Update Payment');

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

        if(User::permission(\Auth::guard('user')->user()->id, "payment_d") === 'false'){
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
                    DELETE FROM payment
                        WHERE code = ?
                ",[$request->id]);

                \DB::connection('tibs')->statement("
                    DELETE FROM payment_map
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

        \App\Log::create('Remove Payment');

        return json_encode($result);
    }

    public function getAll(){
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "payment_r") === 'false'){
            return redirect()->route('user.login');
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

        return \DataTables::of($data)->make(true);
    }

    public function item(){
        $data = \DB::select("
            SELECT * FROM payments 
         ");

        return json_encode($data);
    }

    public function liMap(){
        $data = \DB::connection('tibs')->select("
            SELECT DISTINCT code AS id, name AS name FROM payment_map ORDER BY name ASC
         ");

        return json_encode($data);
    }
}
