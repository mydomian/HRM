<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Category;
use App\Http\Controllers\Controller;
use App\Package;
use App\PackageBuy;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
use Auth;
class ApiController extends Controller
{
    //UserLogin
    public function UserLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'role_as'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
       $user = User::with('usepackage')->where(['email'=>$request['email']])->first();
       if($user){
            if($user['usepackage']['status'] == 'active'){
                $authorization = $user->createToken($user['email'])->accessToken;
                $user->rememberToken = $authorization;
                $user->save();
                if(Auth::attempt(['email'=>$request['email'],'password'=>$request['password'],'role_as'=>$request['role_as']])){
                    return response()->json([
                        'status'=>true,
                        'message'=>'Successfully Login',
                        'rememberToken'=> $user['rememberToken'],
                    ]);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>'Please, Login With Valid Informations'
                    ]);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>'User Package Not Activated'
                ]);
            }
       }else{
            return response()->json([
                'status'=>false,
                'message'=>'User Not Found'
            ]);
       }
    }
    //packages
    public function Packages(){
        return response()->json(['packages'=>Package::latest()->get()]);
    }
    //database create
    public function PackageBuy(Request $request){

        $validator = Validator::make($request->all(), [
            'package_id'=>'required|numeric',
            'company_name'=>'required|string|unique:package_buys','company_name',
            'payment_type'=>'required|string',
            'account_no'=>'required|string',
            'transaction_id'=>'required|string',
            'name'=>'required|min:2|string',
            'email'=>'required|email|unique:users','email'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        //Request Package Information
        $package = Package::where(['id'=>$request['package_id'],'status'=>'active'])->first();
        if($package){
            $usage_package = new PackageBuy;
            $usage_package->package_id = $request['package_id'];
            $usage_package->name = $request['name'];
            $usage_package->email = $request['email'];
            $usage_package->company_name = $request['company_name'];
            $usage_package->payment_type = $request['payment_type'];
            $usage_package->account_no = $request['account_no'];
            $usage_package->transaction_id = $request['transaction_id'];
            $usage_package->duration = $package['duration_days'];
            $usage_package->start_date = Carbon::now();
            $usage_package->end_date = Carbon::now()->addDay($package['duration_days']);
            $usage_package->database_name = preg_replace('/\s+/','',$request['company_name']);
            $usage_package->date = date('Y-m-d');
            $usage_package->save();
            if($usage_package){
                return response()->json([
                    'status'=>true,
                    'message'=>"Package Buy Successfully, Please Wait For Review",
                ],200);
            }
            else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Something Is Wrong To Buy Package",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Package not found",
            ],200);
        }

    }
    //CreateBrand
    public function CreateBrand(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'name'=>'required|unique:brands','name',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                $brand = new Brand;
                $brand->package_buy_id = $user['package_buy_id'];
                $brand->name = $request['name'];
                $brand->save();
                if($brand){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Brand Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Brand",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }
    }
    //BrandLists
    public function BrandLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $brand = Brand::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
            if($brand){
                return response()->json([
                    'status'=>true,
                    'brand_lists'=> $brand,
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Brand Lists Not Found",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Package Not Activated",
            ],200);
        }
    }
    //UpdateBrand
    public function UpdateBrand(Request $request){
        $validator = Validator::make($request->all(), [
            'brand_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $brand = Brand::findOrFail($request['brand_id'])->first();
        $brand->name = $request['name'];
        $brand->save();
        if($brand){
            return response()->json([
                'status'=>true,
                'message'=> "Brand Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteBrand
    public function DeleteBrand(Request $request){
        $brand = Brand::findOrFail($request['brand_id'])->first();
        $brand->delete();
        if($brand){
            return response()->json([
                'status'=>true,
                'message'=> "Brand Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //CreateCategory
    public function CreateCategory(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'name'=>'required|unique:categories','name',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                $category = new Category;
                $category->package_buy_id = $user['package_buy_id'];
                $category->name = $request['name'];
                $category->save();
                if($category){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Category Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Ca",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }
    }
    //CategoryLists
    public function CategoryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $category = Category::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
            if($category){
                return response()->json([
                    'status'=>true,
                    'category_lists'=> $category,
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Category Lists Not Found",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Package Not Activated",
            ],200);
        }
    }
    //UpdateCategory
    public function UpdateCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $category = Category::findOrFail($request['category_id'])->first();
        $category->name = $request['name'];
        $category->save();
        if($category){
            return response()->json([
                'status'=>true,
                'message'=> "Category Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteCategory
    public function DeleteCategory(Request $request){
        $category = Category::findOrFail($request['category_id'])->first();
        $category->delete();
        if($category){
            return response()->json([
                'status'=>true,
                'message'=> "Category Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
}
