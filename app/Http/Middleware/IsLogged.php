<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsLogged
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
        if(auth()->check())
        {
            return new RedirectResponse(url('/panel'));
        }

        return $next($request);
    }
}
