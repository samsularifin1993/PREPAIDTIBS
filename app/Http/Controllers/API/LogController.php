<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function getAll(Request $request){
        if(\Auth::guard('api')->check() == false){
            return response()->json(null, 404);
        }

        $date = '';

        if($request->day != null && $request->month != null && $request->year != null){
            $date = " AND DAY(t1.created_at) = '$request->day' AND  MONTH(t1.created_at) = '$request->month' AND  YEAR(t1.created_at) = '$request->year'";
        }
        elseif($request->day == null && $request->month != null && $request->year != null){
            $date = " AND  MONTH(t1.created_at) = '$request->month' AND  YEAR(t1.created_at) = '$request->year'";
        }
        elseif($request->day == null && $request->month == null && $request->year != null){
            $date = " AND  YEAR(t1.created_at) = '$request->year'";
        }

        $data = \DB::select("
            SELECT
                t1.id AS id,
                t1.activity AS activity,
                t1.ip AS ip,
                t1.location AS location,
                t1.longlat AS longlat,
                t1.created_at AS created_at
            FROM logs AS t1
            LEFT JOIN users AS t2
                ON t1.id_user = t2.id
            WHERE t2.id = '".\Auth::guard('user')->user()->id."'
            ".$date."
            ORDER BY t1.created_at DESC
         ");
        
        $i = 0;
        foreach($data as $d){
            $data[$i]->datetime = "(".\Carbon\Carbon::parse($d->created_at)->diffForHumans().") | ".$data[$i]->created_at;
            $i++;
        }

        $result['error'] = false;
        $result['result'] = $data;
 
        return response()->json($result, 200);
    }
}
