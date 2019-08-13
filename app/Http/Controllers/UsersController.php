<?php

namespace App\Http\Controllers;

use App\Country;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;

class UsersController extends Controller
{
    public function userLoginRegister(){
        return view ('user.login_register');
    }

    public function register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // check if user already exists;
            $usersCount = User::where('email', $data['email'])->count();
            if($usersCount > 0){
                return redirect()->back()->with('flash_message_error', 'Email Already Exits');
            } else {
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->admin = "0";
                $user->save();
                if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
                    Session::put('frontSession', $data['email']);
                    return redirect('/cart');
                }
            }
        }
    }

    public function checkEmail(Request $request){
        $data = $request->all();
        // check if user already exists
        $usersCount = User::where('email', $data['email'])->count();
        if($usersCount > 0){
            echo "false";
        } else {
            echo "true"; die;
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('frontSession');
        return redirect('/');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
                Session::put('frontSession', $data['email']);
                return redirect('/cart');
            } else {
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }

    public function account(){
        $countries = Country::get();
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);
        return view ('user.account', compact('countries', 'userDetails'));
    }
}
