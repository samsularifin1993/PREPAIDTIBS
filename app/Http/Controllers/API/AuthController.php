<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAuthRequest;
use App\User;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    public $loginAfterSignUp = true;
 
    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->nik = $request->nik;
        $user->password = bcrypt($request->password);
        $user->save();
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        \App\Log::createWithApi('Register New User');
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $input = $request->only('nik', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = \JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid NIK or Password',
            ], 401);
        }
 
        \App\Log::createWithApi('Login');

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
 
    public function logout(Request $request)
    { 
        try {
            \JWTAuth::invalidate($request->token);
 
            \App\Log::createWithApi('Logout');

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (\JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 
    public function me(Request $request)
    { 
        $user = \JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }

    public function refresh(Request $request)
    {
        return $this->respondWithToken(auth()->guard('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);
    }
}
