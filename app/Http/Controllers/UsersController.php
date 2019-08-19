<?php

namespace App\Http\Controllers;

use App\Country;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Hash;
use DB;

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

                // Send Verification EMail
                $email = $data['email'];
                $messageData = ['email' => $data['email'],'name' => $data['name'], 'code' => base64_encode($data['email'])];
                Mail::send('email.confirmation', $messageData, function($message) use ($email){
                    $message->to($email)->subject('Email Confirmation');
                    });


                if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
                    Session::put('frontSession', $data['email']);
                    return redirect()->back()->with('flash_message_success', 'Please Confirm Your Email Address');
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
        Session::forget('session_id');
        return redirect('/');
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])){
                $userStatus = User::where('email', $data['email'])->first();
                if($userStatus->status == 0){
                    return redirect()->back()->with('flash_message_error', 'Please Activate Your Account');
                }
                Session::put('frontSession', $data['email']);

                if(!empty(Session::get('session_id'))){
                    $session_id = Session::get('session_id');
                    DB::table('carts')->where('session_id', $session_id)->update(['user_email' => $data['email']]);
                }

                return redirect('/cart');
            } else {
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password');
            }
        }
    }


    public function confirmAccount($email){
        $email = base64_decode($email);
        $userCount = User::where('email', $email)->count();
        if($userCount > 0){
            $userDetails = User::where('email', $email)->first();
            if($userDetails->status == 1){
                return redirect('login-register')->with('flash_message_success', 'Your Email is already activated');
            } else {
                User::where('email', $email)->update(['status' => 1]);

                // Send Welcome Email
                $messageData = ['email' => $email,'name' => $userDetails->name];
                Mail::send('email.welcome', $messageData, function($message) use ($email){
                    $message->to($email)->subject('Welcome To E-Commerce Website');
                });

                return redirect('login-register')->with('flash_message_success', 'Your Email is activated');

            }
        } else {
            abort(404);
        }
    }

    public function account(Request $request){
        $countries = Country::get();
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id);

        if($request->isMethod('post')){
            $data = $request->all();
            $user = User::find($user_id);

            if(empty($data['name'])){
                return redirect()->back()->with('flash_message_error', 'Name is Required');
            }

            if(empty($data['address'])){
                $data['address'] = "";
            }

            if(empty($data['city'])){
                $data['city'] = "";
            }

            if(empty($data['state'])){
                $data['state'] = "";
            }

            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->state = $data['state'];
            $user->country = $data['country'];
            $user->pincode = $data['pincode'];
            $user->mobile = $data['mobile'];
            $user->save();
            return redirect()->back()->with('flash_message_success', 'Account Details has been Updated Successfully');
        }

        return view ('user.account', compact('countries', 'userDetails'));
    }

    public function chkUserPassword(Request $request){
        $data = $request->all();
//        echo "<pre>"; print_r($data); die;
        $current_password = $data['current_pwd'];
        $user_id = Auth::user()->id;
        $check_password = User::where('id', $user_id)->first();
        if(Hash::check($current_password, $check_password->password)){
            echo "true"; die;
        } else {
            echo "False"; die;
        }
    }


    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
//            echo "<pre>"; print_r($data); die;
            $old_password = User::where('id', Auth::user()->id)->first();
            $current_pwd = $data['current_pwd'];
            if(Hash::check($current_pwd, $old_password->password)){
                $new_pwd = bcrypt($data['new_pwd']);
                User::where('id', Auth::user()->id)->update(['password' => $new_pwd]);
                return redirect()->back()->with('flash_message_success', 'Password Changed Successfully');
            } else {
                return redirect()->back()->with('flash_message_error', 'Old Password Doesnot Match');
            }

        }
    }

    public function viewUsers(){
        $users = User::get();
        return view ('admin.users.view_users', compact('users'));
    }

    public function forgetPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $userCount = User::where('email', $data['email'])->count();
            if($userCount == 0){
                return redirect()->back()->with('flash_message_error', 'Email does not exists');
            }
        }
        return view ('user.forget_password');
    }
}
