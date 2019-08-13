<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // check if user already exists
            $usersCount = User::where('email', $data['email'])->count();
            if($usersCount > 0){
                return redirect()->back()->with('flash_message_error', 'E-Mail Already Exists');
            } else {
                echo "Success";
            }
        }
        return view ('user.login_register');
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
}