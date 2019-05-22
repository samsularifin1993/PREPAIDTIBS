<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;

class JWT
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
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid'],400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired'], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\JWTException){
                return response()->json(['status' => 'Authorization Token not found'],404);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\UserNotDefinedException){
                return response()->json(['status' => 'User not found'], 404);
            }else{
                return response()->json(['status' => 'Internal JWT error'], 500);
            }
        }
        return $next($request);
    }
}
