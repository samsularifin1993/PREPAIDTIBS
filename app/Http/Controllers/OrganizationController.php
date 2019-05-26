<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "organization_r") === 'false'){
            return redirect()->route('user.login');
        }

        $permission['r'] = User::permission(\Auth::guard('user')->user()->id, "organization_r");
        $permission['i'] = User::permission(\Auth::guard('user')->user()->id, "organization_i");
        $permission['u'] = User::permission(\Auth::guard('user')->user()->id, "organization_u");
        $permission['d'] = User::permission(\Auth::guard('user')->user()->id, "organization_d");

        \App\Log::create('Show menu Organization');

        return view('organization', ['permission'=>$permission]);
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
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "organization_i") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'opt_regional' => 'required',
            'opt_witel' => 'required',
            'datel' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            $regional = $this->getRegional($request->opt_witel);
            $witel = $this->getWitel($request->opt_witel);

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

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('New Organization');

        return json_encode($result);
    }

    public function storeRegional(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "organization_i") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'regional' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            try {
                \DB::statement("
                    INSERT INTO
                        org_regionals (name, created_at, updated_at)
                        VALUES (?,?,?)
                ",[$request->regional, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Add New Regional');

        return json_encode($result);
    }

    public function storeWitel(Request $request)
    {
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "organization_i") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'opt_regional' => 'required',
            'witel' => 'required',
        ]);

        $result["error"] = false;
        $result["result"] = 'success';

        if ($validator->passes()) {

            \DB::beginTransaction();

            $reg = \DB::connection('tibs')->select("
                SELECT
                    DISTINCT
                    reg_code AS id,
                    regional AS regional
                FROM org
                WHERE reg_code = ?
            ", [$request->opt_regional]);

            try {
                \DB::connection('tibs')->statement("
                    INSERT INTO
                        org (witel, witel_code, regional, reg_code, created_at, updated_at)
                        VALUES (?,?,sysdate,sysdate)
                ",[$request->witel, $reg[0]->regional, $reg[0]->reg_code]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Add New Witel');

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

        if(User::permission(\Auth::guard('user')->user()->id, "organization_u") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                datel_code AS id,
                datel AS datel,   
                witel_code AS witel,
                reg_code AS regional
            FROM org
            WHERE datel_code = ?
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

        if(User::permission(\Auth::guard('user')->user()->id, "organization_u") === 'false'){
            return redirect()->route('user.login');
        }

        $validator = \Validator::make($request->all(), [
            'datel_old' => 'required',
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
                ",[strtoupper($request->datel_old), $request->id]);

                \DB::connection('tibs')->statement("
                    UPDATE org_map
                        SET name=?
                        WHERE code=?
                ",[strtoupper($request->datel_old), $request->id]);

                \DB::commit();
            } catch (\Throwable $e) {
                \DB::rollback();
                throw $e;
            }

            return json_encode($result);
        }

        $result["error"] = true;
        $result["result"] = $validator->errors();

        \App\Log::create('Update Organization');

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

        if(User::permission(\Auth::guard('user')->user()->id, "organization_d") === 'false'){
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
                    DELETE FROM org
                        WHERE datel_code = ?
                ",[$request->id]);

                \DB::connection('tibs')->statement("
                    DELETE FROM org_map
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

        \App\Log::create('Remove Organization');

        return json_encode($result);
    }

    public function getAll(){
        if(\Auth::guard('user')->check() == false){
            return redirect()->route('user.login');
        }

        if(User::permission(\Auth::guard('user')->user()->id, "organization_r") === 'false'){
            return redirect()->route('user.login');
        }

        $data = \DB::connection('tibs')->select("
            SELECT
                datel_code AS id,
                datel AS datel,   
                witel AS witel,
                regional AS regional,
                TO_CHAR(created_at, 'dd-Mon-yyyy hh24:mi:ss') AS created,
                TO_CHAR(updated_at, 'dd-Mon-yyyy hh24:mi:ss') AS updated
            FROM org
         ");

        return \DataTables::of($data)->make(true);
    }

    public function item(){
        $data = \DB::connection('tibs')->select("
            SELECT
                code AS id,
                name AS name,
                description AS description,
                created_at AS created_at,
                updated_at AS updated_at
            FROM channel
         ");

        return json_encode($data);
    }

    public function liRegional(){
        $data = \DB::connection('tibs')->select("
            SELECT DISTINCT reg_code AS id, regional AS name FROM org
         ");

        return json_encode($data);
    }

    public function liWitel($id){
        $data = \DB::connection('tibs')->select("
            SELECT DISTINCT witel_code AS id, witel AS name, reg_code FROM org WHERE reg_code = '$id'
         ");

        return json_encode($data);
    }

    public function liOrgMap(){
        $data = \DB::connection('tibs')->select("
            SELECT * FROM org ORDER BY id ASC
         ");

        foreach($data as $d){
            $d->name = $d->regional.' / '.$d->witel.' / '.$d->datel;
        }

        return json_encode($data);
    }
}
