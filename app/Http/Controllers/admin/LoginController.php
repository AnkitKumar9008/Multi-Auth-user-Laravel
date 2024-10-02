<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;

class LoginController extends Controller
{
    //admin login
    public function index(){
        return view("admin.login");
    }

    public function adminLogin(Request $request){
        $userData = Validator::make(
            $request->all(),
            [
                'email'=>'required|email|max:255',
                'password'=>'required|max:255',
            ]
        );
        if($userData->fails()){
            return redirect()->back()->withErrors($userData)->withInput();
        }else{
            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    if(Auth::guard('admin')->user()->role != "admin"){
                        Auth::guard('admin')->logout();
                        return redirect()->route('admin.login')->with('error','You are not authorized to access this page');
                    }
                    return redirect()->route('admin.dashboard');
                } else {
                    return back()->withErrors([
                        'password' => 'Password is incorrect.',
                    ])->withInput($request->only('email'));
                }
            } else {
                return back()->withErrors([
                    'email' => 'Email is incorrect.',
                ])->withInput($request->only('email'));
            }
        }
    }

    //admin logout 
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','Admin User Log Out!');
    }

}
