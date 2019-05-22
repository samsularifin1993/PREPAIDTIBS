<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AuthorizationController extends Controller
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

        if(User::permission(\Auth::guard('user')->user()->id, "role_r") === 'false'){
            return redirect()->route('user.login');
        }

        $permission['r'] = User::permission(\Auth::guard('user')->user()->id, "role_r");
        $permission['i'] = User::permission(\Auth::guard('user')->user()->id, "role_i");
        $permission['u'] = User::permission(\Auth::guard('user')->user()->id, "role_u");
        $permission['d'] = User::permission(\Auth::guard('user')->user()->id, "role_d");

        \App\Log::create('Show menu Role');

        return view('authorization',['permission'=>$permission]);
    }

    public function get()
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "role_r") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            SELECT * FROM authorizations 
        ");

        return response()->json($data);
    }

    public function post(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "role_i") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            INSERT INTO authorizations(
                name,role_r,role_i,role_u,role_d,user_r,user_i,user_u,user_d,channel_r,channel_i,channel_u,channel_d,organization_r,organization_i,organization_u,organization_d,payment_r,payment_i,payment_u,payment_d,product_family_r,product_family_i,product_family_u,product_family_d,product_r,product_i,product_u,product_d,error_r,error_i,error_u,error_d,v_dashboard_admin,v_dashboard_revenue,r_trx_success,r_trx_reject,r_revenue,source_script,created_by,updated_by,created_at,updated_at
                )
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) 
        ", [$request->name, $request->role_r, $request->role_i, $request->role_u, $request->role_d, $request->user_r, $request->user_i, $request->user_u, $request->user_d, $request->channel_r, $request->channel_i, $request->channel_u, $request->channel_d, $request->organization_r, $request->organization_i, $request->organization_u, $request->organization_d, $request->payment_r, $request->payment_i, $request->payment_u, $request->payment_d, $request->product_family_r, $request->product_family_i, $request->product_family_u, $request->product_family_d, $request->product_r, $request->product_i, $request->product_u, $request->product_d, $request->error_r, $request->error_i, $request->error_u, $request->error_d, $request->v_dashboard_admin, $request->v_dashboard_revenue, $request->r_trx_success, $request->r_trx_reject, $request->r_revenue, $request->source_script, \Auth::guard('user')->user()->id,\Auth::guard('user')->user()->id,date('Y-m-d H:i:s'),date('Y-m-d H:i:s')]);

        \App\Log::create('New Role');

        return response()->json($data);
    }

    public function put(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "role_u") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            UPDATE authorizations SET name=?, role_r=?, role_i=?, role_u=?, role_d=?, user_r=?, user_i=?, user_u=?, user_d=?, channel_r=?, channel_i=?, channel_u=?, channel_d=?, organization_r=?, organization_i=?, organization_u=?, organization_d=?, payment_r=?, payment_i=?, payment_u=?, payment_d=?, product_family_r=?, product_family_i=?, product_family_u=?, product_family_d=?, product_r=?, product_i=?, product_u=?, product_d=?, error_r=?, error_i=?, error_u=?, error_d=?, v_dashboard_admin=?, v_dashboard_revenue=?, r_trx_success=?, r_trx_reject=?, r_revenue=?, source_script=?, updated_by=?,updated_at=? WHERE id=?
        ",[$request->name, $request->role_r, $request->role_i, $request->role_u, $request->role_d, $request->user_r, $request->user_i, $request->user_u, $request->user_d, $request->channel_r, $request->channel_i, $request->channel_u, $request->channel_d, $request->organization_r, $request->organization_i, $request->organization_u, $request->organization_d, $request->payment_r, $request->payment_i, $request->payment_u, $request->payment_d, $request->product_family_r, $request->product_family_i, $request->product_family_u, $request->product_family_d, $request->product_r, $request->product_i, $request->product_u, $request->product_d, $request->error_r, $request->error_i, $request->error_u, $request->error_d, $request->v_dashboard_admin, $request->v_dashboard_revenue, $request->r_trx_success, $request->r_trx_reject, $request->r_revenue, $request->source_script, \Auth::guard('user')->user()->id,date('Y-m-d H:i:s'),$request->id]);

        \App\Log::create('Config Role');

        return response()->json($data);
    }

    public function delete(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "role_d") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::select("
            DELETE FROM authorizations WHERE id=? 
        ",[$request->id]);

        \App\Log::create('Remove Role');

        return response()->json($data);
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

    public function item(){
        $data = \DB::select("
            SELECT * FROM authorizations 
         ");

        return json_encode($data);
    }
}
