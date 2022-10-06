<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    //login
    public function Login(Request $request){
        if($request->isMethod('post')){
            $rules = [
                'role'=>'required','email'=>'required|email','password'=>'required'
            ];
            $customMsg = [
                'role.required' => "Choose your role",'password.required' => "Password is required",'email.required' => "Email is required",'email.email' => "Valid email is required"
            ];
            $this->validate($request,$rules,$customMsg);
            $data = $request->all();
            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],'role_as'=>$data['role']])){

                 return view('layouts.admin_layouts.admin_dashboard');

            }else{
                return redirect('/admin/login')->with("error","Please Login With Valid Info");
            }
        }
        return view('admin.login');
    }
    //dashboard
    public function Index(){
        return view('admin.index');
    }
    //RolePermission
    public function RolePermission(){
        $roles = Admin::latest()->get();
        return view('admin.role_permission',compact('roles'));
    }
    //RoleCreate
    public function RoleCreate(Request $request){
        $rules = [
            'email'=>'required|email|unique:admins','email',
            'phone'=>'required|unique:admins','phone'
        ];
        $customMsg = [
            'email.required' => "Email is required",'email.email' => "Valid email is required",'email.unique' => "Email already exits",
            'phone.required' => "Phone is required",'phone.unique' => "Phone already exits",

        ];
        $this->validate($request,$rules,$customMsg);
        $role = new Admin;
        $role->name = $request['name'];
        $role->email = $request['email'];
        $role->phone = $request['phone'];
        $role->password = Hash::make($request['password']);
        $role->role_as = $request['role_as'];
        $role->save();
        return redirect()->back()->with("message","Role Assign Successfully");
    }
    //RoleEdit
    public function RoleEdit($role_id){
        $role = Admin::findOrfail($role_id);
        return $role;
    }
    //Logout
    public function Logout(){
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->with("message","Logout Successfully");
    }
}
