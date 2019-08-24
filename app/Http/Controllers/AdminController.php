<?php

namespace App\Http\Controllers;

use App\Admin;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Session;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->input();

            $adminCount = Admin::where(['username' => $data['username'], 'password' => md5($data['password']), 'status' => 1])->count();
            if($adminCount > 0 ){
                Session::put('adminSession', $data['username']);
                return redirect()->route('admin.dashboard');
            } else {
                return redirect('/admin')->with('flash_message_error', 'Invalid Username or Password');
            }

        }
        return view ('admin.admin_login');
    }

    public function dashboard(){
        return view ('admin.dashboard');
    }

    public function settings(){
        $adminDetails = Admin::where(['username' => Session::get('adminSession')])->first();
        return view ('admin.settings', compact('adminDetails'));
    }

    public function chkPassword(Request $request){
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $adminCount = Admin::where(['username' => Session::get('adminSession'), 'password' => md5($data['current_pwd'])])->count();
        if($adminCount == 1){
            echo "true"; die;
        } else {
            echo "false"; die;
        }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $adminCount = Admin::where(['username' => Session::get('adminSession'), 'password' => md5($data['current_pwd'])])->count();
            if($adminCount == 1){
               $password = md5($data['new_pwd']);
               Admin::where('username',Session::get('adminSession'))->update(['password' => $password]);
               return redirect('/admin/settings')->with('flash_message_success', 'Password Updated Successfully');
            } else {
                return redirect('/admin/settings')->with('flash_message_error', 'Incorrect Current Password');
            }
        }
    }

    public function logout(){
        Session::flush();
        return redirect('/admin')->with('flash_message_success', 'Logout Successfull');
    }

    public function viewAdmins(){
        $admins = Admin::get();
        return view ('admin.admins.view_admins', compact('admins'));
    }

    public function addAdmin(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $adminCount = Admin::where('username', $data['username'])->count();
            if($adminCount > 0){
                return redirect()->back()->with('flash_message_error', 'Username already exits');
            } else {
                if($data['type'] == "Admin"){
                    $admin = new Admin;
                    $admin->type = $data['type'];
                    $admin->username = $data['username'];
                    $admin->password = md5($data['password']);
                    if(empty($data['status'])){
                        $admin->status = 0;
                    }
                    $admin->save();
                    return redirect()->back()->with('flash_message_success', 'Admin Created');
                } else if ($data['type'] == "SubAdmin"){
                    $admin = new Admin;
                    $admin->type = $data['type'];
                    $admin->username = $data['username'];
                    $admin->password = md5($data['password']);

                    if(empty($data['status'])){
                        $admin->status = 0;
                    }

                    if(empty($data['categories_access'])){
                        $admin->categories_access = 1;
                    } else {
                        $admin->categories_access = 0;
                    }

                    if(empty($data['product_access'])){
                        $admin->product_access = 1;
                    } else {
                        $admin->product_access = 0;
                    }

                    if(empty($data['orders_access'])){
                        $admin->orders_access = 1;
                    } else {
                        $admin->orders_access = 0;
                    }

                    if(empty($data['users_access'])){
                        $admin->users_access = 1;
                    } else {
                        $admin->users_access = 0;
                    }

                    $admin->save();
                    return redirect()->back()->with('flash_message_success', 'Sub Admin Created');
                }

            }
        }
        return view ('admin.admins.add_admin');
    }
}
