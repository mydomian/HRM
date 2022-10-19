<?php

namespace App\Http\Controllers\Api;

use App\AccArea;
use App\AccCategory;
use App\AccCustomerSupplier;
use App\AccType;
use App\BankAccCategory;
use App\Brand;
use App\CashCounter;
use App\Category;
use App\City;
use App\District;
use App\Driver;
use App\Http\Controllers\Controller;
use App\IncomeExpenseAccType;
use App\IncomeExpensePaymentMethodType;
use App\LotGallary;
use App\Package;
use App\PackageBuy;
use App\PaymentMethod;
use App\Product;
use App\ProductionType;
use App\Thana;
use App\Union;
use App\Unit;
use App\User;
use App\Vehicale;
use App\WareHouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
use Illuminate\Support\Facades\File;
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
                        'user_data'=>$user
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
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
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
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //BrandLists
    public function BrandLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $brand = Brand::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($brand){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $brand,
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
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Brand
    public function Brand(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $brand = Brand::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($brand){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $brand,
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
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
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

        $brand = Brand::findOrFail($request['brand_id']);
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
        $brand = Brand::findOrFail($request['brand_id']);
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
    //SingelBrand
    public function SingelBrand($brand_id){
        $brand = Brand::where('id',$brand_id)->select('id','name')->first();
        if($brand){
            return response()->json([
                'status'=>true,
                'lists'=> $brand,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateCategory
    public function CreateCategory(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
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
                            'message'=>"Something Is Wrong To Create Category",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //CategoryLists
    public function CategoryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $category = Category::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($category){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $category,
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
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //category
    public function Category(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $category = Category::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($category){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $category,
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
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
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
        $category = Category::findOrFail($request['category_id']);
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
        $category = Category::findOrFail($request['category_id']);
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
    //SingelCategory
    public function SingelCategory($category_id){
        $category = Category::where('id',$category_id)->select('id','name')->first();
        if($category){
            return response()->json([
                'status'=>true,
                'lists'=> $category,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateUnit
    public function CreateUnit(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $unit = new Unit;
                    $unit->package_buy_id = $user['package_buy_id'];
                    $unit->name = $request['name'];
                    $unit->save();
                    if($unit){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Unit Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Unit",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //UnitLists
    public function UnitLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $unit = Unit::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($unit){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $unit,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Unit Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //unit
    public function Unit(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $unit = Unit::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($unit){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $unit,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Unit Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateUnit
    public function UpdateUnit(Request $request){
        $validator = Validator::make($request->all(), [
            'unit_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $unit = Unit::findOrFail($request['unit_id']);
        $unit->name = $request['name'];
        $unit->save();
        if($unit){
            return response()->json([
                'status'=>true,
                'message'=> "Unit Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteUnit
    public function DeleteUnit(Request $request){
        $unit = Unit::findOrFail($request['unit_id']);
        $unit->delete();
        if($unit){
            return response()->json([
                'status'=>true,
                'message'=> "Unit Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingelUnit
    public function SingelUnit($unit_id){
        $unit = Unit::where('id',$unit_id)->select('id','name')->first();
        if($unit){
            return response()->json([
                'status'=>true,
                'lists'=> $unit,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateLotGallary
    public function CreateLotGallary(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $lot_gallary = new LotGallary;
                    $lot_gallary->package_buy_id = $user['package_buy_id'];
                    $lot_gallary->name = $request['name'];
                    $lot_gallary->save();
                    if($lot_gallary){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Lot Gallary Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Lot Gallary",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //LotGallaryLists
    public function LotGallaryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $lot_gallary = LotGallary::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($lot_gallary){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $lot_gallary,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Lot Gallary Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //LotGallary
    public function LotGallary(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $lot_gallary = LotGallary::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($lot_gallary){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $lot_gallary,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Lot Gallary Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateLotGallary
    public function UpdateLotGallary(Request $request){
        $validator = Validator::make($request->all(), [
            'lot_gallary_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $lot_gallary = LotGallary::findOrFail($request['lot_gallary_id']);
        $lot_gallary->name = $request['name'];
        $lot_gallary->save();
        if($lot_gallary){
            return response()->json([
                'status'=>true,
                'message'=> "Lot Gallary Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteLotGallary
    public function DeleteLotGallary(Request $request){
        $lot_gallary = LotGallary::findOrFail($request['lot_gallary_id']);
        $lot_gallary->delete();
        if($lot_gallary){
            return response()->json([
                'status'=>true,
                'message'=> "Lot Gallary Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleLotGallary
     public function SingleLotGallary($lot_gallary_id){
        $lot_gallary = LotGallary::where('id',$lot_gallary_id)->select('id','name')->first();
        if($lot_gallary){
            return response()->json([
                'status'=>true,
                'lists'=> $lot_gallary,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateCusSupAcc
    public function CreateCusSupAcc(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'acc_name'=>'required',
                        'email'=>'required|email|unique:acc_customer_suppliers','email',
                        'phone'=>'required|numeric|unique:acc_customer_suppliers','phone',
                        'address'=>'required',
                        'word'=>'required',
                        'acc_area'=>'required',
                        'acc_opening_balance'=>'required|numeric',
                        'acc_hold_balance'=>'required|numeric',
                        'profile_image'=>'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        'nid_image'=>'required|file',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }

                    $digits = 15;
                    //profile image
                    $profile = time().'.'.$request->profile_image->extension();
                    $request->profile_image->move(public_path('images/profile_image'), $profile);
                    //nid image
                    $nid = time().'.'.$request->nid_image->extension();
                    $request->nid_image->move(public_path('images/nid_image'), $nid);

                    $acc_cus_sup = new AccCustomerSupplier;
                    $acc_cus_sup->package_buy_id = $user['package_buy_id'];
                    $acc_cus_sup->acc_name = $request['acc_name'];
                    $acc_cus_sup->acc_no = rand(pow(10, $digits-1), pow(10, $digits)-1);
                    $acc_cus_sup->email = $request['email'];
                    $acc_cus_sup->phone = $request['phone'];
                    $acc_cus_sup->address = $request['address'];
                    $acc_cus_sup->word = $request['word'];
                    $acc_cus_sup->acc_area = $request['acc_area'];
                    $acc_cus_sup->acc_opening_balance = $request['acc_opening_balance'];
                    $acc_cus_sup->acc_hold_balance = $request['acc_hold_balance'];
                    $acc_cus_sup->profile_image = $_SERVER['HTTP_HOST'].'/public/images/profile_image/'.$profile;
                    $acc_cus_sup->nid_image = $_SERVER['HTTP_HOST'].'/public/images/nid_image/'.$nid;
                    $acc_cus_sup->date = date('Y-m-d');
                    $acc_cus_sup->save();
                    if($acc_cus_sup){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Customer & Supplier Account Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Account",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //CusSupAccLists
    public function CusSupAccLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_cus_sup = AccCustomerSupplier::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
                if($acc_cus_sup){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_cus_sup,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Customer & Supplier Account Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //CusSupAcc
    public function CusSupAcc(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_cus_sup = AccCustomerSupplier::where('package_buy_id',$user['package_buy_id'])->select('id','acc_name')->orderBy('id','DESC')->get();
                if($acc_cus_sup){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_cus_sup,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Customer & Supplier Account Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateCusSupAcc
    public function UpdateCusSupAcc(Request $request){
        if($request->isMethod('post')){

                $validator = Validator::make($request->all(), [
                    'acc_name'=>'required',
                    'email'=>'required|email',
                    'phone'=>'required|numeric',
                    'address'=>'required',
                    'word'=>'required',
                    'acc_area'=>'required',
                    'acc_opening_balance'=>'required|numeric',
                    'acc_hold_balance'=>'required|numeric',
                    'profile_image'=>'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'nid_image'=>'file',
                    'cus_sup_acc_id'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }

                $acc_cus_sup = AccCustomerSupplier::findOrFail($request['cus_sup_acc_id']);
                //profile image
                if($request->hasFile('profile_image')){
                    $image_name = basename($acc_cus_sup['profile_image']);
                    File::delete(public_path("images/profile_image/".$image_name));
                    $profile = time().'.'.$request->profile_image->extension();
                    $request->profile_image->move(public_path('images/profile_image'), $profile);
                }
                //nid image
                if($request->hasFile('nid_image')){
                    $image_name = basename($acc_cus_sup['nid_image']);
                    File::delete(public_path("images/nid_image/".$image_name));
                    $nid = time().'.'.$request->nid_image->extension();
                    $request->nid_image->move(public_path('images/nid_image'), $nid);
                }
                $acc_cus_sup->acc_name = $request['acc_name'];
                $acc_cus_sup->email = $request['email'];
                $acc_cus_sup->phone = $request['phone'];
                $acc_cus_sup->address = $request['address'];
                $acc_cus_sup->word = $request['word'];
                $acc_cus_sup->acc_area = $request['acc_area'];
                $acc_cus_sup->acc_opening_balance = $request['acc_opening_balance'];
                $acc_cus_sup->acc_hold_balance = $request['acc_hold_balance'];
                if(isset($profile)){
                    $acc_cus_sup->profile_image = $_SERVER['HTTP_HOST'].'/public/images/profile_image/'.$profile;
                }
                if(isset($nid)){
                    $acc_cus_sup->nid_image = $_SERVER['HTTP_HOST'].'/public/images/nid_image/'.$nid;
                }
                $acc_cus_sup->save();
                if($acc_cus_sup){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Customer & Supplier Account Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Update Account",
                    ],200);
                }
        }
    }
    //SingleCusSupAcc
    public function SingleCusSupAcc($cus_sup_acc_id){
        $cus_sup_acc = AccCustomerSupplier::where('id',$cus_sup_acc_id)->select('id','acc_name')->first();
        if($cus_sup_acc){
            return response()->json([
                'status'=>true,
                'lists'=> $cus_sup_acc,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateProduct
    public function CreateProduct(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'brand_id'=>'required',
                        'category_id'=>'required',
                        'unit_id'=>'required',
                        'acc_cus_sup_id'=>'required',
                        'lot_gallary_id'=>'required',
                        'product_name'=>'required',
                        'product_model'=>'required',
                        'product_image'=>'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        'serial_no'=>'required',
                        'supplier_price'=>'required|numeric',
                        'our_price'=>'required|numeric',
                        'khat_acc_id'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }


                    //product image
                    $product_image = time().'.'.$request->product_image->extension();
                    $request->product_image->move(public_path('images/product_image'), $product_image);

                    $digits = 15;
                    $product = new Product;
                    $product->package_buy_id = $user['package_buy_id'];
                    $product->brand_id = $request['brand_id'];
                    $product->category_id = $request['category_id'];
                    $product->unit_id = $request['unit_id'];
                    $product->acc_cus_sup_id = $request['acc_cus_sup_id'];
                    $product->lot_gallary_id = $request['lot_gallary_id'];
                    $product->product_name = $request['product_name'];
                    $product->product_model = $request['product_model'];
                    $product->product_image = $_SERVER['HTTP_HOST'].'/public/images/product_image/'.$product_image;
                    $product->batch_no = rand(pow(10, $digits-1), pow(10, $digits)-1);
                    $product->serial_no = $request['serial_no'];
                    $product->supplier_price = $request['supplier_price'];
                    $product->our_price = $request['our_price'];
                    // $product->khat_acc_id = $request['khat_acc_id'];
                    $product->save();
                    if($product){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Product Added Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Add Product",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //ProductLists
    public function ProductLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $product = Product::with('brand','category','unit','lot_gallary','customer_supplier')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
                if($product){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $product,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Product Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateProduct
    public function UpdateProduct(Request $request){
        if($request->isMethod('post')){
                $validator = Validator::make($request->all(), [
                    'brand_id'=>'required',
                    'categroy_id'=>'required',
                    'unit_id'=>'required',
                    'acc_cus_sup_id'=>'required',
                    'lot_gallary_id'=>'required',
                    'product_name'=>'required',
                    'product_model'=>'required',
                    'product_image'=>'file|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'serial_no'=>'required',
                    'supplier_price'=>'required|numeric',
                    'our_price'=>'required|numeric',
                    'khat_acc_id'=>'required',
                    'product_id'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }

                $product = Product::where('id',$request['product_id'])->first();
                //product image
                if($request->hasFile('product_image')){
                    $image_name = basename($product['product_image']);
                    File::delete(public_path("images/product_image/".$image_name));

                    $product_image = time().'.'.$request->product_image->extension();
                    $request->product_image->move(public_path('images/product_image'), $product_image);
                }

                $product->brand_id = $request['brand_id'];
                $product->categroy_id = $request['categroy_id'];
                $product->unit_id = $request['unit_id'];
                $product->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $product->lot_gallary_id = $request['lot_gallary_id'];
                $product->product_name = $request['product_name'];
                $product->product_model = $request['product_model'];
                if(isset($product_image)){
                    $product->product_image = $_SERVER['HTTP_HOST'].'/public/images/product_image/'.$product_image;
                }
                $product->serial_no = $request['serial_no'];
                $product->supplier_price = $request['supplier_price'];
                $product->our_price = $request['our_price'];
                // $product->khat_acc_id = $request['khat_acc_id'];
                $product->save();
                if($product){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Product Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Update Product",
                    ],200);
                }
        }
    }
    //EnableProduct
    public function EnableProduct(Request $request){
        $product = Product::findOrFail($request['product_id']);
        $product->status = 'active';
        $product->save();
        if($product){
            return response()->json([
                'status'=>true,
                'message'=>"Product Enabled Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Enable Product",
            ],200);
        }
    }
    //DisableProduct
    public function DisableProduct(Request $request){
        $product = Product::findOrFail($request['product_id']);
        $product->status = 'deactive';
        $product->save();
        if($product){
            return response()->json([
                'status'=>true,
                'message'=>"Product Disabled Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Disable Product",
            ],200);
        }
    }
    //SingleProduct
    public function SingleProduct($product_id){
        $product = Product::with('brand','category','unit','lot_gallary','customer_supplier')->where('id',$product_id)->first();
        if($product){
            return response()->json([
                'status'=>true,
                'lists'=> $product,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateWareHouse
    public function CreateWareHouse(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $warehouse = new WareHouse;
                    $warehouse->package_buy_id = $user['package_buy_id'];
                    $warehouse->name = $request['name'];
                    $warehouse->save();
                    if($warehouse){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Ware House Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Ware House",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //WareHouseLists
    public function WareHouseLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $warehouse = WareHouse::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($warehouse){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $warehouse,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Ware House Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //WareHouse
    public function WareHouse(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $warehouse = WareHouse::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($warehouse){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $warehouse,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Ware House Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateWareHouse
    public function UpdateWareHouse(Request $request){
        $validator = Validator::make($request->all(), [
            'warehouse_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $warehouse = WareHouse::findOrFail($request['warehouse_id']);
        $warehouse->name = $request['name'];
        $warehouse->save();
        if($warehouse){
            return response()->json([
                'status'=>true,
                'message'=> "Ware House Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteWareHouse
    public function DeleteWareHouse(Request $request){
        $warehouse = WareHouse::findOrFail($request['warehouse_id']);
        $warehouse->delete();
        if($warehouse){
            return response()->json([
                'status'=>true,
                'message'=> "Ware House Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleWareHouse
    public function SingleWareHouse($ware_house_id){
        $warehouse = WareHouse::where('id',$ware_house_id)->select('id','name')->first();
        if($warehouse){
            return response()->json([
                'status'=>true,
                'lists'=> $warehouse,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateThana
    public function CreateThana(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                        'city_id'=>'required',
                        'district_id'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $thana = new Thana;
                    $thana->package_buy_id = $user['package_buy_id'];
                    $thana->city_id = $request['city_id'];
                    $thana->district_id = $request['district_id'];
                    $thana->name = $request['name'];
                    $thana->save();
                    if($thana){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Thana Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Thana",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //ThanaLists
    public function ThanaLists(Request $request){


        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $thana = Thana::with('city','district')->where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);

                if($thana){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $thana,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Thana Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Thana
    public function Thana(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $thana = Thana::with('city','district')->where('package_buy_id',$user['package_buy_id'])->select('id','city_id','district_id','name')->orderBy('id','DESC')->get();
                if($thana){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $thana,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Thana Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateThana
    public function UpdateThana(Request $request){
        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'district_id'=>'required',
            'thana_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $thana = Thana::findOrFail($request['thana_id']);
        $thana->name = $request['name'];
        $thana->save();
        if($thana){
            return response()->json([
                'status'=>true,
                'message'=> "Thana Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteThana
    public function DeleteThana(Request $request){
        $warehouse = Thana::findOrFail($request['thana_id']);
        $warehouse->delete();
        if($warehouse){
            return response()->json([
                'status'=>true,
                'message'=> "Thana Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleThana
     public function SingleThana($thana_id){
        $thana = Thana::with('city','district')->where('id',$thana_id)->first();
        if($thana){
            return response()->json([
                'status'=>true,
                'lists'=> $thana,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
     //CreateCity
     public function CreateCity(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $city = new City;
                    $city->package_buy_id = $user['package_buy_id'];
                    $city->name = $request['name'];
                    $city->save();
                    if($city){
                        return response()->json([
                            'status'=>true,
                            'message'=>"City Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create City",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //CityLists
    public function CityLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $city = City::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($city){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $city,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"City Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //City
    public function City(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $city = City::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($city){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $city,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"City Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateCity
    public function UpdateCity(Request $request){
        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $city = City::findOrFail($request['city_id']);
        $city->name = $request['name'];
        $city->save();
        if($city){
            return response()->json([
                'status'=>true,
                'message'=> "City Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteCity
    public function DeleteCity(Request $request){
        $city = City::findOrFail($request['city_id']);
        $city->delete();
        if($city){
            return response()->json([
                'status'=>true,
                'message'=> "City Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleCity
    public function SingleCity($city_id){
        $city = City::where('id',$city_id)->select('id','name')->first();
        if($city){
            return response()->json([
                'status'=>true,
                'lists'=> $city,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateDistrict
    public function CreateDistrict(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                        'city_id'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $district = new District;
                    $district->package_buy_id = $user['package_buy_id'];
                    $district->name = $request['name'];
                    $district->city_id = $request['city_id'];
                    $district->save();
                    if($district){
                        return response()->json([
                            'status'=>true,
                            'message'=>"District Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create District",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //DistrictLists
    public function DistrictLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $district = District::with('city')->where('package_buy_id',$user['package_buy_id'])->select('id','city_id','name')->orderBy('id','DESC')->paginate(15);
                if($district){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $district,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"District Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //District
    public function District(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $district = District::with('city')->where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($district){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $district,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"District Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateDistrict
    public function UpdateDistrict(Request $request){

        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $district = District::findOrFail($request['district_id']);
        $district->name = $request['name'];
        $district->city_id = $request['city_id'];
        $district->save();
        if($district){
            return response()->json([
                'status'=>true,
                'message'=> "District Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteDistrict
    public function DeleteDistrict(Request $request){
        $district = District::findOrFail($request['district_id']);
        $district->delete();
        if($district){
            return response()->json([
                'status'=>true,
                'message'=> "District Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleDistrict
    public function SingleDistrict($distict_id){
        $district = District::with('city')->where('id',$distict_id)->select('id','city_id','name')->first();
        if($district){
            return response()->json([
                'status'=>true,
                'lists'=> $district,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateUnion
    public function CreateUnion(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                        'city_id'=>'required',
                        'district_id'=>'required',
                        'thana_id'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $union = new Union;
                    $union->package_buy_id = $user['package_buy_id'];
                    $union->name = $request['name'];
                    $union->city_id = $request['city_id'];
                    $union->district_id = $request['district_id'];
                    $union->thana_id = $request['thana_id'];
                    $union->save();
                    if($union){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Union Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Union",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //UnionLists
    public function UnionLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $union = Union::with('city','district','thana')->where('package_buy_id',$user['package_buy_id'])->select('id','city_id','district_id','thana_id','name')->orderBy('id','DESC')->paginate(15);
                if($union){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $union,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Union Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Union
    public function Union(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $union = Union::with('city','district','thana')->where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($union){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $union,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Union Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateUnion
    public function UpdateUnion(Request $request){

        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'district_id'=>'required',
            'thana_id'=>'required',
            'union_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $union = Union::findOrFail($request['union_id']);
        $union->name = $request['name'];
        $union->city_id = $request['city_id'];
        $union->district_id = $request['district_id'];
        $union->thana_id = $request['thana_id'];
        $union->save();
        if($union){
            return response()->json([
                'status'=>true,
                'message'=> "Union Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteUnion
    public function DeleteUnion(Request $request){
        $union = Union::findOrFail($request['union_id']);
        $union->delete();
        if($union){
            return response()->json([
                'status'=>true,
                'message'=> "Union Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleUnion
     public function SingleUnion($union_id){
        $union = Union::with('city','district','thana')->where('id',$union_id)->select('id','city_id','district_id','thana_id','name')->first();
        if($union){
            return response()->json([
                'status'=>true,
                'lists'=> $union,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateIncExpAccType
    public function CreateIncExpAccType(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $in_exp_acc_type = new IncomeExpenseAccType;
                    $in_exp_acc_type->package_buy_id = $user['package_buy_id'];
                    $in_exp_acc_type->name = $request['name'];
                    $in_exp_acc_type->save();
                    if($in_exp_acc_type){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Income Expense Account Type Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Income Expense Account Type",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //IncExpAccTypeLists
    public function IncExpAccTypeLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $inc_exp_acc_type = IncomeExpenseAccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($inc_exp_acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $inc_exp_acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Income Expense Acc Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //IncExpAccType
    public function IncExpAccType(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $inc_exp_acc_type = IncomeExpenseAccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($inc_exp_acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $inc_exp_acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Income Expense Account Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateIncExpAccType
    public function UpdateIncExpAccType(Request $request){

        $validator = Validator::make($request->all(), [
            'inc_exp_acc_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $inc_exp_acc_type = IncomeExpenseAccType::findOrFail($request['inc_exp_acc_id']);
        $inc_exp_acc_type->name = $request['name'];
        $inc_exp_acc_type->save();
        if($inc_exp_acc_type){
            return response()->json([
                'status'=>true,
                'message'=> "Income Expense Account Type Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }

    //DeleteIncExpAccType
    public function DeleteIncExpAccType(Request $request){
        $inc_exp_acc_id = IncomeExpenseAccType::findOrFail($request['inc_exp_acc_id']);
        $inc_exp_acc_id->delete();
        if($inc_exp_acc_id){
            return response()->json([
                'status'=>true,
                'message'=> "Income Expense Account Type Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleIncExpAccType
    public function SingleIncExpAccType($inc_exp_acc_id){
        $inc_exp_acc_type = IncomeExpenseAccType::where('id',$inc_exp_acc_id)->select('id','name')->first();
        if($inc_exp_acc_type){
            return response()->json([
                'status'=>true,
                'lists'=> $inc_exp_acc_type,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateIncExpPayMethod
    public function CreateIncExpPayMethod(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $in_exp_pay_method = new IncomeExpensePaymentMethodType;
                    $in_exp_pay_method->package_buy_id = $user['package_buy_id'];
                    $in_exp_pay_method->name = $request['name'];
                    $in_exp_pay_method->save();
                    if($in_exp_pay_method){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Income Expense Payment Method Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Income Expense Payment Method",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //IncExpPayMethodLists
    public function IncExpPayMethodLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $inc_exp_pay_method = IncomeExpensePaymentMethodType::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
                if($inc_exp_pay_method){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $inc_exp_pay_method,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Income Expense Payment Method Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //IncExpPayMethod
    public function IncExpPayMethod(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $inc_exp_pay_method = IncomeExpensePaymentMethodType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($inc_exp_pay_method){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $inc_exp_pay_method,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Income Expense Payment Method Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateIncExpPayMethod
    public function UpdateIncExpPayMethod(Request $request){

        $validator = Validator::make($request->all(), [
            'inc_exp_pay_method_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $inc_exp_pay_method = IncomeExpensePaymentMethodType::findOrFail($request['inc_exp_pay_method_id']);
        $inc_exp_pay_method->name = $request['name'];
        $inc_exp_pay_method->save();
        if($inc_exp_pay_method){
            return response()->json([
                'status'=>true,
                'message'=> "Income Expense Payment Method Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteIncExpPayMethod
    public function DeleteIncExpPayMethod(Request $request){

        $inc_exp_pay_method = IncomeExpensePaymentMethodType::findOrFail($request['inc_exp_pay_method_id']);
        $inc_exp_pay_method->delete();
        if($inc_exp_pay_method){
            return response()->json([
                'status'=>true,
                'message'=> "Income Expense Payment Method Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //CreateProductionType
    public function CreateProductionType(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $production_type = new ProductionType;
                    $production_type->package_buy_id = $user['package_buy_id'];
                    $production_type->name = $request['name'];
                    $production_type->save();
                    if($production_type){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Production Type Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Production Type",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //ProductionType
    public function ProductionTypeLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $production_type = ProductionType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($production_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $production_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Production Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //ProductionType
    public function ProductionType(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $production_type = ProductionType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($production_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $production_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Production Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateIncExpPayMethod
    public function UpdateProductionType(Request $request){

        $validator = Validator::make($request->all(), [
            'production_type_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $production_type = ProductionType::findOrFail($request['production_type_id']);
        $production_type->name = $request['name'];
        $production_type->save();
        if($production_type){
            return response()->json([
                'status'=>true,
                'message'=> "Production Type Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteProductionType
    public function DeleteProductionType(Request $request){

        $production_type = ProductionType::findOrFail($request['production_type_id']);
        $production_type->delete();
        if($production_type){
            return response()->json([
                'status'=>true,
                'message'=> "Production Type Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleProductionType
    public function SingleProductionType($production_type_id){
        $production_type = ProductionType::where('id',$production_type_id)->select('id','name')->first();
        if($production_type){
            return response()->json([
                'status'=>true,
                'lists'=> $production_type,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreatePaymentMethod
    public function CreatePaymentMethod(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $payment_method = new PaymentMethod;
                    $payment_method->package_buy_id = $user['package_buy_id'];
                    $payment_method->name = $request['name'];
                    $payment_method->save();
                    if($payment_method){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Payment Method Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Payment Method",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //PaymentMethodLists
    public function PaymentMethodLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $payment_method = PaymentMethod::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($payment_method){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $payment_method,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Payment Method Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //PaymentMethod
    public function PaymentMethod(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();

        if($user){
            if($user['usepackage']['status'] == 'active'){
                $payment_method = PaymentMethod::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($payment_method){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $payment_method,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Payment Method Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdatePaymentMethod
    public function UpdatePaymentMethod(Request $request){

        $validator = Validator::make($request->all(), [
            'pay_method_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $payment_method = PaymentMethod::findOrFail($request['pay_method_id']);
        $payment_method->name = $request['name'];
        $payment_method->save();
        if($payment_method){
            return response()->json([
                'status'=>true,
                'message'=> "PaymentMethod Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeletePaymentMethod
    public function DeletePaymentMethod(Request $request){

        $payment_method = PaymentMethod::findOrFail($request['pay_method_id']);
        $payment_method->delete();
        if($payment_method){
            return response()->json([
                'status'=>true,
                'message'=> "Payment Method Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SinglePaymentMethod
    public function SinglePaymentMethod($payment_method_id){
        $payment_method = PaymentMethod::where('id',$payment_method_id)->select('id','name')->first();
        if($payment_method){
            return response()->json([
                'status'=>true,
                'lists'=> $payment_method,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateAccArea
    public function CreateAccArea(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $acc_area = new AccArea;
                    $acc_area->package_buy_id = $user['package_buy_id'];
                    $acc_area->name = $request['name'];
                    $acc_area->save();
                    if($acc_area){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Account Area Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Account Area",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //AccAreaLists
    public function AccAreaLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_area = AccArea::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($acc_area){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_area,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Area Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //AccArea
    public function AccArea(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_area = AccArea::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($acc_area){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_area,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Area Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateAccArea
    public function UpdateAccArea(Request $request){
        $validator = Validator::make($request->all(), [
            'acc_area_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_area = AccArea::findOrFail($request['acc_area_id']);
        $acc_area->name = $request['name'];
        $acc_area->save();
        if($acc_area){
            return response()->json([
                'status'=>true,
                'message'=> "Account Area Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteAccArea
    public function DeleteAccArea(Request $request){
        $acc_area = AccArea::findOrFail($request['acc_area_id']);
        $acc_area->delete();
        if($acc_area){
            return response()->json([
                'status'=>true,
                'message'=> "Account Area Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleAccArea
     public function SingleAccArea($acc_area_id){
        $acc_area = AccArea::where('id',$acc_area_id)->select('id','name')->first();
        if($acc_area){
            return response()->json([
                'status'=>true,
                'lists'=> $acc_area,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
     //CreateAccCategory
     public function CreateAccCategory(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $acc_category = new AccCategory;
                    $acc_category->package_buy_id = $user['package_buy_id'];
                    $acc_category->name = $request['name'];
                    $acc_category->save();
                    if($acc_category){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Account Category Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Account Category",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //AccCategoryLists
    public function AccCategoryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_category = AccCategory::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($acc_category){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_category,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Category Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //AccCategory
    public function AccCategory(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_category = AccCategory::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($acc_category){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_category,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Category Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateAccCategory
    public function UpdateAccCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'acc_category_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_category = AccCategory::findOrFail($request['acc_category_id']);
        $acc_category->name = $request['name'];
        $acc_category->save();
        if($acc_category){
            return response()->json([
                'status'=>true,
                'message'=> "Account Category Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteAccCategory
    public function DeleteAccCategory(Request $request){
        $acc_category = AccCategory::findOrFail($request['acc_category_id']);
        $acc_category->delete();
        if($acc_category){
            return response()->json([
                'status'=>true,
                'message'=> "Account Category Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleAccCategory
     public function SingleAccCategory($acc_category_id){
        $acc_category = AccCategory::where('id',$acc_category_id)->select('id','name')->first();
        if($acc_category){
            return response()->json([
                'status'=>true,
                'lists'=> $acc_category,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateAccType
    public function CreateAccType(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $acc_type = new AccType;
                    $acc_type->package_buy_id = $user['package_buy_id'];
                    $acc_type->name = $request['name'];
                    $acc_type->save();
                    if($acc_type){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Account Type Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Account Type",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //AccTypeLists
    public function AccTypeLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_type = AccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Acctype
    public function AccType(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $acc_type = AccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Account Type Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateAccType
    public function UpdateAccType(Request $request){

        $validator = Validator::make($request->all(), [
            'acc_type_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_type = AccType::findOrFail($request['acc_type_id']);
        $acc_type->name = $request['name'];
        $acc_type->save();
        if($acc_type){
            return response()->json([
                'status'=>true,
                'message'=> "Account Type Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteAccType
    public function DeleteAccType(Request $request){
        $acc_type = AccType::findOrFail($request['acc_type_id']);
        $acc_type->delete();
        if($acc_type){
            return response()->json([
                'status'=>true,
                'message'=> "Account Type Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleAccType
     public function SingleAccType($acc_type_id){
        $acc_type = AccType::where('id',$acc_type_id)->select('id','name')->first();
        if($acc_type){
            return response()->json([
                'status'=>true,
                'lists'=> $acc_type,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateBankAccCategory
    public function CreateBankAccCategory(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $bank_acc_cat = new BankAccCategory;
                    $bank_acc_cat->package_buy_id = $user['package_buy_id'];
                    $bank_acc_cat->name = $request['name'];
                    $bank_acc_cat->save();
                    if($bank_acc_cat){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Bank Account Category Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Bank Account Category",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //BankAccCategoryLists
    public function BankAccCategoryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_acc_cat = BankAccCategory::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($bank_acc_cat){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_acc_cat,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Account Category Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //BankAccCategory
    public function BankAccCategory(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_acc_cat = BankAccCategory::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($bank_acc_cat){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_acc_cat,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Account Category Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateBankAccCategory
    public function UpdateBankAccCategory(Request $request){

        $validator = Validator::make($request->all(), [
            'bank_acc_category_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_acc_cat = BankAccCategory::findOrFail($request['bank_acc_category_id']);
        $bank_acc_cat->name = $request['name'];
        $bank_acc_cat->save();
        if($bank_acc_cat){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Account Category Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteBankAccCategory
    public function DeleteBankAccCategory(Request $request){
        $bank_acc_category = BankAccCategory::findOrFail($request['bank_acc_category_id']);
        $bank_acc_category->delete();
        if($bank_acc_category){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Account Category Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleBankAccCategory
     public function SingleBankAccCategory($bank_acc_category_id){
        $bank_acc_category = BankAccCategory::where('id',$bank_acc_category_id)->select('id','name')->first();
        return $bank_acc_category;
        if($bank_acc_category){
            return response()->json([
                'status'=>true,
                'lists'=> $bank_acc_category,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateCashCounter
    public function CreateCashCounter(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $cash_counter = new CashCounter;
                    $cash_counter->package_buy_id = $user['package_buy_id'];
                    $cash_counter->name = $request['name'];
                    $cash_counter->save();
                    if($cash_counter){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Cash Counter Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Cash Counter",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //CashCounterLists
    public function CashCounterLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $cash_counter = CashCounter::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($cash_counter){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $cash_counter,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Cash Counter Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //CashCounter
    public function CashCounter(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $cash_counter = CashCounter::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($cash_counter){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $cash_counter,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Cash Counter Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateCashCounter
    public function UpdateCashCounter(Request $request){

        $validator = Validator::make($request->all(), [
            'cash_counter_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_acc_cat = CashCounter::findOrFail($request['cash_counter_id']);
        $bank_acc_cat->name = $request['name'];
        $bank_acc_cat->save();
        if($bank_acc_cat){
            return response()->json([
                'status'=>true,
                'message'=> "Cash Counter Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteCashCounter
    public function DeleteCashCounter(Request $request){
        $bank_acc_category = CashCounter::findOrFail($request['cash_counter_id']);
        $bank_acc_category->delete();
        if($bank_acc_category){
            return response()->json([
                'status'=>true,
                'message'=> "Cash Counter Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleCashCounter
     public function SingleCashCounter($cash_counter_id){
        $cash_counter = CashCounter::where('id',$cash_counter_id)->select('id','name')->first();
        return $cash_counter;
        if($cash_counter){
            return response()->json([
                'status'=>true,
                'lists'=> $cash_counter,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateVehicale
    public function CreateVehicale(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'city_id'=>'required',
                        'district_id'=>'required',
                        'thana_id'=>'required',
                        'union_id'=>'required',
                        'vehicle_name'=>'required',
                        'vehicle_type'=>'required',
                        'vehicle_no'=>'required|unique:vehicales','vehicle_no',
                        'vehicle_reg_no'=>'required|unique:vehicales','vehicle_reg_no',
                        'owner_name'=>'required',
                        'father_name'=>'required',
                        'owner_phone'=>'required|numeric',
                        'owner_post_office'=>'required',
                        'owner_village'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $vehicale = new Vehicale;
                    $vehicale->package_buy_id = $user['package_buy_id'];
                    $vehicale->city_id = $request['city_id'];
                    $vehicale->district_id = $request['district_id'];
                    $vehicale->thana_id = $request['thana_id'];
                    $vehicale->union_id = $request['union_id'];
                    $vehicale->vehicle_name = $request['vehicle_name'];
                    $vehicale->vehicle_type = $request['vehicle_type'];
                    $vehicale->vehicle_no = $request['vehicle_no'];
                    $vehicale->vehicle_reg_no = $request['vehicle_reg_no'];
                    $vehicale->owner_name = $request['owner_name'];
                    $vehicale->father_name = $request['father_name'];
                    $vehicale->owner_phone = $request['owner_phone'];
                    $vehicale->owner_post_office = $request['owner_post_office'];
                    $vehicale->owner_village = $request['owner_village'];
                    $vehicale->save();
                    if($vehicale){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Vehicale Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Vehicale",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //VehicaleLists
    public function VehicaleLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $vehicale = Vehicale::with('city','district','thana','union')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
                if($vehicale){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $vehicale,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Vehicales Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Vehicale
    public function Vehicale(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $vehicale = Vehicale::with('city','district','thana','union')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
                if($vehicale){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $vehicale,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Vehicales Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateVehicale
    public function UpdateVehicale(Request $request){

        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'district_id'=>'required',
            'thana_id'=>'required',
            'union_id'=>'required',
            'vehicle_name'=>'required',
            'vehicle_type'=>'required',
            'vehicle_no'=>'required',
            'vehicle_reg_no'=>'required',
            'owner_name'=>'required',
            'father_name'=>'required',
            'owner_phone'=>'required|numeric',
            'owner_post_office'=>'required',
            'owner_village'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $vehicale = Vehicale::findOrFail($request['vehicale_id']);
        $vehicale->city_id = $request['city_id'];
        $vehicale->district_id = $request['district_id'];
        $vehicale->thana_id = $request['thana_id'];
        $vehicale->union_id = $request['union_id'];
        $vehicale->vehicle_name = $request['vehicle_name'];
        $vehicale->vehicle_type = $request['vehicle_type'];
        $vehicale->vehicle_no = $request['vehicle_no'];
        $vehicale->vehicle_reg_no = $request['vehicle_reg_no'];
        $vehicale->owner_name = $request['owner_name'];
        $vehicale->father_name = $request['father_name'];
        $vehicale->owner_phone = $request['owner_phone'];
        $vehicale->owner_post_office = $request['owner_post_office'];
        $vehicale->owner_village = $request['owner_village'];
        $vehicale->save();
        if($vehicale){
            return response()->json([
                'status'=>true,
                'message'=> "Vehicale Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteVehicale
    public function DeleteVehicale(Request $request){
        $vehicale = Vehicale::findOrFail($request['vehicale_id']);
        $vehicale->delete();
        if($vehicale){
            return response()->json([
                'status'=>true,
                'message'=> "Vehicale Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleVehicale
     public function SingleVehicale($vehicale_id){
        $vehicale = Vehicale::where('id',$vehicale_id)->first();
        if($vehicale){
            return response()->json([
                'status'=>true,
                'lists'=> $vehicale,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateDriver
    public function CreateDriver(Request $request){

        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'city_id'=>'required',
                        'district_id'=>'required',
                        'thana_id'=>'required',
                        'union_id'=>'required',
                        'vehicle_id'=>'required',
                        'driver_name'=>'required',
                        'driver_phone'=>'required|numeric|unique:drivers','driver_phone',
                        'father_name'=>'required',
                        'driver_post_office'=>'required',
                        'driver_village'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $driver = new Driver;
                    $driver->package_buy_id = $user['package_buy_id'];
                    $driver->vehicle_id = $request['vehicle_id'];
                    $driver->city_id = $request['city_id'];
                    $driver->district_id = $request['district_id'];
                    $driver->thana_id = $request['thana_id'];
                    $driver->union_id = $request['union_id'];
                    $driver->driver_name = $request['driver_name'];
                    $driver->driver_phone = $request['driver_phone'];
                    $driver->father_name = $request['father_name'];
                    $driver->driver_post_office = $request['driver_post_office'];
                    $driver->driver_village = $request['driver_village'];
                    $driver->save();
                    if($driver){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Driver Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Driver",
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Package Not Activated",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Invalid Token",
                ],200);
            }
        }
    }
    //DriverLists
    public function DriverLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $driver = Driver::with('city','district','thana','union')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
                if($driver){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $driver,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Driver Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //Driver
    public function Driver(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $driver = Driver::with('city','district','thana','union')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
                if($driver){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $driver,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Driver Lists Not Found",
                    ],200);
                }
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>"Package Not Activated",
                ],200);
            }
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Invalid Token",
            ],200);
        }
    }
    //UpdateDriver
    public function UpdateDriver(Request $request){

        $validator = Validator::make($request->all(), [
            'city_id'=>'required',
            'district_id'=>'required',
            'thana_id'=>'required',
            'union_id'=>'required',
            'vehicle_id'=>'required',
            'driver_name'=>'required',
            'driver_phone'=>'required|numeric',
            'father_name'=>'required',
            'driver_post_office'=>'required',
            'driver_village'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $driver = Driver::findOrFail($request['driver_id']);
        $driver->vehicle_id = $request['vehicle_id'];
        $driver->city_id = $request['city_id'];
        $driver->district_id = $request['district_id'];
        $driver->thana_id = $request['thana_id'];
        $driver->union_id = $request['union_id'];
        $driver->driver_name = $request['driver_name'];
        $driver->driver_phone = $request['driver_phone'];
        $driver->father_name = $request['father_name'];
        $driver->driver_post_office = $request['driver_post_office'];
        $driver->driver_village = $request['driver_village'];
        $driver->save();
        if($driver){
            return response()->json([
                'status'=>true,
                'message'=> "Driver Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteDriver
    public function DeleteDriver(Request $request){
        $driver = Driver::findOrFail($request['driver_id']);
        $driver->delete();
        if($driver){
            return response()->json([
                'status'=>true,
                'message'=> "Driver Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleDriver
     public function SingleDriver($driver_id){
        $driver = Driver::where('id',$driver_id)->first();
        if($driver){
            return response()->json([
                'status'=>true,
                'lists'=> $driver,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
}

