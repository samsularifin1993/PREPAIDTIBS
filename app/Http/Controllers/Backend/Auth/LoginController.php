<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->only('showLoginForm');
    }

    protected function showLoginForm()
    {
        return view('backend.auth.login');
    }

    protected function guard()
    {
        return \Auth::guard('admin');
    }

    // public function getLoginForm()
    // {
    //     if(\Auth::guard('admin')->check()){
    //         return redirect()->intended('admin/home');
    //     }

    //     return view('backend.auth.login');
    // }	
    
    // public function auth(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');
         
    //     if (auth()->guard('admin')->attempt($credentials)) 
    //     {
    //         return redirect()->intended('admin/index');
    //     }
    //     else
    //     {
    //         return redirect()->intended('admin/login')->with('status', 'Invalid Login Credentials !');
    //     }
    // }
    
    // public function getLogout() 
    // {
    //     auth()->guard('admin')->logout();
    //     return redirect()->intended('admin/login');
    // }
}
