<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:user')->only('showLoginForm');
        date_default_timezone_set('Asia/Jakarta');
    }

    protected function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    protected function guard()
    {
        return \Auth::guard('user');
    }

    protected function username()
    {
        return 'nik';
    }

    // public function getLoginForm()
    // {
    //     if(\Auth::guard('user')->check()){
    //         return redirect()->intended('home');
    //     }

    //     return view('frontend.auth.login');
    // }	
    
    // public function auth(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');
         
    //     if (auth()->guard('user')->attempt($credentials)) 
    //     {
    //         return redirect()->intended('index');
    //     }
    //     else
    //     {
    //         return redirect()->intended('login')->with('status', 'Invalid Login Credentials !');
    //     }
    // }
    
    // public function getLogout() 
    // {
    //     auth()->guard('user')->logout();
    //     return redirect()->intended('login');
    // }

    /*
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }*/

    protected function authenticated(Request $request, $user)
    {
        $request->session()->put('islogin', 'true');
        //\App\Log::create('Login');
    }

    public function logout(Request $request)
    {
        \App\Log::create('Logout');

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
