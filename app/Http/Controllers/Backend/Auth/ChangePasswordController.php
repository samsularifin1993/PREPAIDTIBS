<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        // return view('backend.auth.passwords.change')->with(
        //     ['message' => null, 'alert' => '']
        // );

        return view('admin_passwords_change')->with(
            ['message' => null, 'alert' => '']
        );
    }

    public function change(Request $request)
    {
        $request->validate($this->rules());

        $data = \DB::table('admins')
                ->select('admins.password')
                ->where('admins.id', auth()->guard('admin')->id())
                ->get();

        if ( !(\Hash::check($request->password, $data[0]->password)) )
        {
            // return view('backend.auth.passwords.change')->with(
            //     ['message' => 'Old password not valid', 'alert' => 'danger']
            // );
            
            return view('admin_passwords_change')->with(
                ['message' => 'Old password not valid', 'alert' => 'danger']
            );
        }

        if ( !($request->new_password === $request->new_password_confirmation) )
        {
            // return view('backend.auth.passwords.change')->with(
            //     ['message' => 'New password confirm not matched', 'alert' => 'danger']
            // );

            return view('admin_passwords_change')->with(
                ['message' => 'New password confirm not matched', 'alert' => 'danger']
            );
        }

        \DB::table('admins')
            ->where('admins.id', auth()->guard('admin')->id())
            ->update(array('password' => bcrypt($request->new_password)));

        // return view('backend.auth.passwords.change')->with(
        //     ['message' => 'Password changed successfully', 'alert' => 'success']
        // );

        return view('admin_passwords_change')->with(
            ['message' => 'Password changed successfully', 'alert' => 'success']
        );
    }

    public function rules()
    {
        return [
            'password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|min:6'
        ];
    }
}
