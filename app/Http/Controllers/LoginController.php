<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;
use Hash;
class LoginController extends Controller
{
    // This method will show login page for customer 

    public function index()
    {
        return view("login");
    }

    public function authenticate(Request $request){
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
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                    $authUser = Auth::user();
                    return redirect()->route('account.dashboard');
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

    public function register(){
        return view('register');
    }

    public function registerAuthenticate(Request $request){
        $userRegister = Validator::make(
            $request->all(),
            [
                'name'=>'required|max:255',
                'email'=>'required|Unique:Users',
                //'mobile' => 'required|numeric|digits_between:10,15',
                'password'=> 'required|max:255',
            ]
        );
        if($userRegister->fails()){
            return redirect()->back()->withErrors($userRegister)->withInput();
        }

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = Hash::make($request->password);
        $users->role = 'user';
        $users->save();
        
        return redirect()->route('account.register')->with('success', 'User Register Successfully');
    }

    public function logout(){
        Auth::logout(); 
        return redirect()->route('account.login');
    }
}
