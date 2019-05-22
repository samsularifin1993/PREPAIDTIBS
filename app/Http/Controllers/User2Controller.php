<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class User2Controller extends Controller
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

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $permission['r'] = User::permission(\Auth::guard('user')->user()->id, "user_r");
        $permission['i'] = User::permission(\Auth::guard('user')->user()->id, "user_i");
        $permission['u'] = User::permission(\Auth::guard('user')->user()->id, "user_u");
        $permission['d'] = User::permission(\Auth::guard('user')->user()->id, "user_d");

        \App\Log::create('Show menu User');

        return view('user2', ['permission'=>$permission]);
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

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'nik' => 'required|numeric',
            'password' => 'required_with:password_confirm|same:password_confirm',
            'password_confirm' => 'required',
            'role' => 'required|numeric',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::statement("
                    INSERT INTO
                        users (name, nik, password, id_role, created_at, updated_at)
                        VALUES (?,?,?,?,?,?)
                ",[$request->name, $request->nik, bcrypt($request->password), $request->role, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('New User');

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

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            SELECT
                t1.id AS id,
                t1.name AS name,
                t1.nik AS nik,
                t1.id_role AS role
            FROM users AS t1
            LEFT JOIN authorizations AS t2
                ON t1.id_role = t2.id    
            WHERE t1.id = ?
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

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'name_old' => 'required',
            'nik_old' => 'required|numeric',
            'role_old' => 'required|numeric',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::statement("
                    UPDATE users
                        SET name=?, nik=?, id_role=?, updated_at=?
                        WHERE id=?
                ",[$request->name_old, $request->nik_old, $request->role_old, date('Y-m-d H:i:s'), $request->id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Update User');

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

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
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
                \DB::statement("
                    DELETE FROM users
                        WHERE id = ?
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

        \App\Log::create('Remove User');

        return json_encode($result);
    }

    public function getAll(){
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            SELECT
                t1.id AS id,
                t1.name AS name,
                t1.nik AS nik,
                t2.name AS role
            FROM users AS t1
            LEFT JOIN authorizations AS t2
                ON t1.id_role = t2.id
            ORDER BY t1.id ASC
         ");

        return \DataTables::of($data)->make(true);
    }

    public function item(){
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "user_r") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            SELECT * FROM users 
         ");

        return json_encode($data);
    }
}
