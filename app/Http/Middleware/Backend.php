<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Backend
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
        if(!empty(auth()->guard('admin')->id()))
        {
            $data = \DB::table('admins')
                    ->select('admins.id')
                    ->where('admins.id',auth()->guard('admin')->id())
                    ->get();
            
            if (!$data[0]->id)
            {
                return redirect()->intended('admin/login/')->with('status', 'You do not have access to admin side');
            }
            return $next($request);
        }
        else 
        {
            return redirect()->intended('admin/login/')->with('status', 'Please Login to access admin area');
        }
    }
}
