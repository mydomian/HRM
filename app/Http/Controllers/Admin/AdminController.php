<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                return redirect('admin/dashborad')->with("message","Successfully Login");
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
    //Logout
    public function Logout(){
        Auth::guard('admin')->logout();
        return redirect('/admin/login')->with("message","Logout Successfully");
    }
}
