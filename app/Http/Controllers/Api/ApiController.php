<?php

namespace App\Http\Controllers\Api;

use App\AccCustomerSupplier;
use App\Brand;
use App\Category;
use App\Http\Controllers\Controller;
use App\LotGallary;
use App\Package;
use App\PackageBuy;
use App\Product;
use App\Unit;
use App\User;
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
    //CreateUnit
    public function CreateUnit(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
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
        }
    }
    //UnitLists
    public function UnitLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $unit = Unit::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
            if($unit){
                return response()->json([
                    'status'=>true,
                    'unit_lists'=> $unit,
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
        $unit = Unit::findOrFail($request['unit_id'])->first();
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
        $unit = Unit::findOrFail($request['unit_id'])->first();
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
    //CreateLotGallary
    public function CreateLotGallary(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
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
        }
    }
    //LotGallaryLists
    public function LotGallaryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $lot_gallary = LotGallary::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
            if($lot_gallary){
                return response()->json([
                    'status'=>true,
                    'lot_gallary_lists'=> $lot_gallary,
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
        $lot_gallary = LotGallary::findOrFail($request['lot_gallary_id'])->first();
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
        $lot_gallary = LotGallary::findOrFail($request['lot_gallary_id'])->first();
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
    //CreateCusSupAcc
    public function CreateCusSupAcc(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
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
                $acc_cus_sup->profile_image = $_SERVER['HTTP_HOST'].'/images/profile_image/'.$profile;
                $acc_cus_sup->nid_image = $_SERVER['HTTP_HOST'].'/images/nid_image/'.$nid;
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
        }
    }
    //CusSupAccLists
    public function CusSupAccLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $acc_cus_sup = AccCustomerSupplier::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
            if($acc_cus_sup){
                return response()->json([
                    'status'=>true,
                    'cus_supp_acc_lists'=> $acc_cus_sup,
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
                    $acc_cus_sup->profile_image = $_SERVER['HTTP_HOST'].'/images/profile_image/'.$profile;
                }
                if(isset($nid)){
                    $acc_cus_sup->nid_image = $_SERVER['HTTP_HOST'].'/images/nid_image/'.$nid;
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
    //CreateProduct
    public function CreateProduct(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'brand_id'=>'required',
                    'categroy_id'=>'required',
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


                //profile image
                $product_image = time().'.'.$request->product_image->extension();
                $request->product_image->move(public_path('images/product_image'), $product_image);
                $digits = 15;
                $product = new Product;
                $product->package_buy_id = $user['package_buy_id'];
                $product->brand_id = $request['brand_id'];
                $product->categroy_id = $request['categroy_id'];
                $product->unit_id = $request['unit_id'];
                $product->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $product->lot_gallary_id = $request['lot_gallary_id'];
                $product->product_name = $request['product_name'];
                $product->product_model = $request['product_model'];
                $product->product_image = $_SERVER['HTTP_HOST'].'/images/product_image/'.$product_image;
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
        }
    }
    //ProductLists
    public function ProductLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user['usepackage']['status'] == 'active'){
            $product = Product::where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
            if($product){
                return response()->json([
                    'status'=>true,
                    'product_lists'=> $product,
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
    }
}
