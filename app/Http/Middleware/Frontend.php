<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Frontend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty(auth()->guard('user')->id()))
        {
            // $data = \DB::table('users')
            //         ->select('users.id', 'users.id_role')
            //         ->where('users.id',auth()->guard('user')->id())
            //         ->get();
            
            // if (!$data[0]->id)
            // {
            //     return redirect()->intended('login')->with('status', 'You do not have access to user side');
            // }else{
            //     $request->session()->put('user_role', $data[0]->id_role);
            // }
            return $next($request);
        }
        else 
        {
            return redirect()->intended('login')->with('status', 'Please Login to access user area');
        }
    }
}
