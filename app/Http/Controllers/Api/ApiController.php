<?php

namespace App\Http\Controllers\Api;

use App\AccArea;
use App\AccCategory;
use App\AccCustomerSupplier;
use App\AccType;
use App\BankAccCategory;
use App\Brand;
use App\Bank;
use App\BankAccType;
use App\BankBranch;
use App\CashCounter;
use App\Category;
use App\City;
use App\Delivery;
use App\DeliveryChallan;
use App\DeliveryChallanItem;
use App\DeliveryHistory;
use App\DeliveryItem;
use App\Designation;
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
use App\ProductOrderBy;
use App\Purchase;
use App\PurchaseChallan;
use App\PurchaseChallanItem;
use App\PurchaseItem;
use App\PurchaseQuotation;
use App\PurchaseQuotationItem;
use App\Receipt;
use App\ReceiptChallan;
use App\ReceiptChallanItem;
use App\ReceiptHistory;
use App\ReceiptItem;
use App\Sale;
use App\SaleChallan;
use App\SaleChallanItem;
use App\SaleItem;
use App\SaleQuotation;
use App\SaleQuotationItem;
use App\Stock;
use App\StockHistory;
use App\StockTransfer;
use App\StockTransferItem;
use App\Thana;
use App\Union;
use App\Unit;
use App\User;
use App\Vehicale;
use App\VehicaleType;
use DB;
use App\WareHouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Str;
use Illuminate\Support\Facades\File;
use Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use JetBrains\PhpStorm\Pure;
use Laravel\Ui\Presets\React;
use mysqli;

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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $brand = Brand::findOrFail($request['id']);
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
        $brand = Brand::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $category = Category::findOrFail($request['id']);
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
        $category = Category::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $unit = Unit::findOrFail($request['id']);
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
        $unit = Unit::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $lot_gallary = LotGallary::findOrFail($request['id']);
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
        $lot_gallary = LotGallary::findOrFail($request['id']);
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

    //CreateProductOrderBy
    public function CreateProductOrderBy(Request $request){
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
                    $product_order_by = new ProductOrderBy;
                    $product_order_by->package_buy_id = $user['package_buy_id'];
                    $product_order_by->name = $request['name'];
                    $product_order_by->save();
                    if($product_order_by){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Product Order By Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Product Order By",
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
    //ProductOrderByLists
    public function ProductOrderByLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $product_order_by = ProductOrderBy::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($product_order_by){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $product_order_by,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Product Order BY Lists Not Found",
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
    //ProductOrderBy
    public function ProductOrderBy(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $product_order_by = ProductOrderBy::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($product_order_by){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $product_order_by,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Product Order BY Lists Not Found",
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
    //UpdateProductOrderBy
    public function UpdateProductOrderBy(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $product_order_by = ProductOrderBy::findOrFail($request['id']);
        $product_order_by->name = $request['name'];
        $product_order_by->save();
        if($product_order_by){
            return response()->json([
                'status'=>true,
                'message'=> "Product Order BY Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteProductOrderBy
    public function DeleteProductOrderBy(Request $request){
        $product_order_by = ProductOrderBy::findOrFail($request['id']);
        $product_order_by->delete();
        if($product_order_by){
            return response()->json([
                'status'=>true,
                'message'=> "Product Order By Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingleProductOrderBy
    public function SingleProductOrderBy($product_order_by_id){
        $product_order_by = ProductOrderBy::where('id',$product_order_by_id)->select('id','name')->first();
        if($product_order_by){
            return response()->json([
                'status'=>true,
                'lists'=> $product_order_by,
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

                $acc_cus_sup = AccCustomerSupplier::findOrFail($request['id']);
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
                if(isset($request['acc_name'])){
                    $acc_cus_sup->acc_name = $request['acc_name'];
                }
                if(isset($request['email'])){
                    $acc_cus_sup->email = $request['email'];
                }
                if(isset($request['phone'])){
                    $acc_cus_sup->phone = $request['phone'];
                }
                if(isset($request['address'])){
                    $acc_cus_sup->address = $request['address'];
                }
                if(isset($request['word'])){
                    $acc_cus_sup->word = $request['word'];
                }
                if(isset($request['acc_area'])){
                    $acc_cus_sup->acc_area = $request['acc_area'];
                }
                if(isset($request['acc_opening_balance'])){
                    $acc_cus_sup->acc_opening_balance = $request['acc_opening_balance'];
                }
                if(isset($request['acc_hold_balance'])){
                    $acc_cus_sup->acc_hold_balance = $request['acc_hold_balance'];
                }

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
                        // 'khat_acc_id'=>'required',
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
    //product
    public function Product(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $product = Product::where('package_buy_id',$user['package_buy_id'])->select('id','product_name as name')->orderBy('id','DESC')->get();
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

                $product = Product::where('id',$request['id'])->first();
                //product image
                if($request->hasFile('product_image')){
                    $image_name = basename($product['product_image']);
                    File::delete(public_path("images/product_image/".$image_name));
                    $product_image = time().'.'.$request->product_image->extension();
                    $request->product_image->move(public_path('images/product_image'), $product_image);
                }
                if(isset($request['brand_id'])){
                    $product->brand_id = $request['brand_id'];
                }
                if(isset($request['category_id'])){
                    $product->categroy_id = $request['category_id'];
                }
                if(isset($request['unit_id'])){
                    $product->unit_id = $request['unit_id'];
                }
                if(isset($request['acc_cus_sup_id'])){
                    $product->acc_cus_sup_id = $request['acc_cus_sup_id'];
                }
                if(isset($request['lot_gallary_id'])){
                    $product->lot_gallary_id = $request['lot_gallary_id'];
                }
                if(isset($request['product_name'])){
                    $product->product_name = $request['product_name'];
                }
                if(isset($request['product_model'])){
                    $product->product_model = $request['product_model'];
                }
                if(isset($product_image)){
                    $product->product_image = $_SERVER['HTTP_HOST'].'/public/images/product_image/'.$product_image;
                }
                if(isset($request['serial_no'])){
                    $product->serial_no = $request['serial_no'];
                }
                if(isset($request['supplier_price'])){
                    $product->supplier_price = $request['supplier_price'];
                }
                if(isset($request['our_price'])){
                    $product->our_price = $request['our_price'];
                }
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
        $product = Product::findOrFail($request['id']);
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
        $product = Product::findOrFail($request['id']);
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
    //DeleteProduct
    public function DeleteProduct(Request $request){
        $product = Product::where('id',$request['id'])->delete();
        if($product){
            return response()->json([
                'status'=>true,
                'message'=>"Product Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Product",
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $warehouse = WareHouse::findOrFail($request['id']);
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
        $warehouse = WareHouse::findOrFail($request['id']);
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
                $thana = Thana::with('city','district')->where('package_buy_id',$user['package_buy_id'])->select('id','city_id','district_id','name')->orderBy('id','DESC')->paginate(15);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $thana = Thana::findOrFail($request['id']);
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
        $warehouse = Thana::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $city = City::findOrFail($request['id']);
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
        $city = City::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $district = District::findOrFail($request['id']);
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
        $district = District::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $union = Union::findOrFail($request['id']);
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
        $union = Union::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $inc_exp_acc_type = IncomeExpenseAccType::findOrFail($request['id']);
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
        $inc_exp_acc_id = IncomeExpenseAccType::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $inc_exp_pay_method = IncomeExpensePaymentMethodType::findOrFail($request['id']);
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

        $inc_exp_pay_method = IncomeExpensePaymentMethodType::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $production_type = ProductionType::findOrFail($request['id']);
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

        $production_type = ProductionType::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $payment_method = PaymentMethod::findOrFail($request['id']);
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

        $payment_method = PaymentMethod::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_area = AccArea::findOrFail($request['id']);
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
        $acc_area = AccArea::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_category = AccCategory::findOrFail($request['id']);
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
        $acc_category = AccCategory::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $acc_type = AccType::findOrFail($request['id']);
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
        $acc_type = AccType::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_acc_cat = BankAccCategory::findOrFail($request['id']);
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
        $bank_acc_category = BankAccCategory::findOrFail($request['id']);
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
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_acc_cat = CashCounter::findOrFail($request['id']);
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
        $bank_acc_category = CashCounter::findOrFail($request['id']);
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
                $vehicale = Vehicale::with('city','district','thana','union','driver')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
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
                $vehicale = Vehicale::with('city','district','thana','union','driver')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
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

        $vehicale = Vehicale::findOrFail($request['id']);
        if(isset($request['city_id'])){
            $vehicale->city_id = $request['city_id'];
        }
        if(isset($request['district_id'])){
            $vehicale->district_id = $request['district_id'];
        }
        if(isset($request['thana_id'])){
            $vehicale->thana_id = $request['thana_id'];
        }
        if(isset($request['union_id'])){
            $vehicale->union_id = $request['union_id'];
        }
        if(isset($request['vehicle_name'])){
            $vehicale->vehicle_name = $request['vehicle_name'];
        }
        if(isset($request['vehicle_type'])){
            $vehicale->vehicle_type = $request['vehicle_type'];
        }
        if(isset($request['vehicle_no'])){
            $vehicale->vehicle_no = $request['vehicle_no'];
        }
        if( isset($request['vehicle_reg_no'])){
            $vehicale->vehicle_reg_no = $request['vehicle_reg_no'];
        }
        if(isset($request['owner_name'])){
            $vehicale->owner_name = $request['owner_name'];
        }
        if(isset($request['father_name'])){
            $vehicale->father_name = $request['father_name'];
        }
        if(isset($request['owner_phone'])){
            $vehicale->owner_phone = $request['owner_phone'];
        }
        if(isset($request['owner_post_office'])){
            $vehicale->owner_post_office = $request['owner_post_office'];
        }
        if(isset($request['owner_village'])){
            $vehicale->owner_village = $request['owner_village'];
        }

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
        $vehicale = Vehicale::findOrFail($request['id']);
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
                $driver = Driver::with('city','district','thana','union','vehicale')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->paginate(15);
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
                $driver = Driver::with('city','district','thana','union','vehicale')->where('package_buy_id',$user['package_buy_id'])->orderBy('id','DESC')->get();
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

        $driver = Driver::findOrFail($request['id']);
        if(isset($request['vehicle_id'])){
            $driver->vehicle_id = $request['vehicle_id'];
        }
        if(isset($request['city_id'])){
            $driver->city_id = $request['city_id'];
        }
        if(isset($request['district_id'])){
            $driver->district_id = $request['district_id'];
        }
        if(isset($request['thana_id'])){
            $driver->thana_id = $request['thana_id'];
        }
        if(isset($request['union_id'])){
            $driver->union_id = $request['union_id'];
        }
        if(isset($request['driver_name'])){
            $driver->driver_name = $request['driver_name'];
        }
        if(isset($request['driver_phone'])){
            $driver->driver_phone = $request['driver_phone'];
        }
        if(isset($request['father_name'])){
            $driver->father_name = $request['father_name'];
        }
        if(isset($request['driver_post_office'])){
            $driver->driver_post_office = $request['driver_post_office'];
        }
        if(isset($request['driver_village'])){
            $driver->driver_village = $request['driver_village'];
        }
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
        $driver = Driver::findOrFail($request['id']);
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
    //CreateVehicaleType
    public function CreateVehicaleType(Request $request){

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
                    $vehicale_type = new VehicaleType;
                    $vehicale_type->package_buy_id = $user['package_buy_id'];
                    $vehicale_type->name = $request['name'];
                    $vehicale_type->save();
                    if($vehicale_type){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Vehicale Type Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Vehicale Type",
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
    //VehicaleTypeLists
    public function VehicaleTypeLists(Request $request){

        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $vehicale_type = VehicaleType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($vehicale_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $vehicale_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Vehicale Type Lists Not Found",
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
    //VehicaleType
    public function VehicaleType(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $vehicale_type = VehicaleType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($vehicale_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $vehicale_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Vehicale Type Lists Not Found",
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
    //UpdateVehicaleType
    public function UpdateVehicaleType(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $vehicale_type = VehicaleType::findOrFail($request['id']);
        $vehicale_type->name = $request['name'];
        $vehicale_type->save();
        if($vehicale_type){
            return response()->json([
                'status'=>true,
                'message'=> "Vehicale Type Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteVehicaleType
    public function DeleteVehicaleType(Request $request){
        $vehicale_type = VehicaleType::findOrFail($request['id']);
        $vehicale_type->delete();
        if($vehicale_type){
            return response()->json([
                'status'=>true,
                'message'=> "Vehicale Type Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
    //SingelVehicaleType
    public function SingelVehicaleType($vehicale_type_id){
        $vehicale_type = VehicaleType::where('id',$vehicale_type_id)->select('id','name')->first();
        if($vehicale_type){
            return response()->json([
                'status'=>true,
                'lists'=> $vehicale_type,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateBank
    public function CreateBank(Request $request){
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
                    $bank = new Bank;
                    $bank->package_buy_id = $user['package_buy_id'];
                    $bank->name = $request['name'];
                    $bank->save();
                    if($bank){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Bank Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Bank",
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
    //BankLists
    public function BankLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank = Bank::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($bank){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Lists Not Found",
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
    //Bank
    public function Bank(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank = Bank::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($bank){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Lists Not Found",
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
    //UpdateBank
    public function UpdateBank(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank = Bank::findOrFail($request['id']);
        $bank->name = $request['name'];
        $bank->save();
        if($bank){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteBank
    public function DeleteBank(Request $request){
        $bank = Bank::findOrFail($request['id']);
        $bank->delete();
        if($bank){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleBankAccCategory
     public function SingleBank($bank_id){
        $bank = Bank::where('id',$bank_id)->select('id','name')->first();

        if($bank){
            return response()->json([
                'status'=>true,
                'lists'=> $bank,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateBankBranch
    public function CreateBankBranch(Request $request){
        if($request->isMethod('post')){
            $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
            if($user){
                if($user['usepackage']['status'] == 'active'){
                    $validator = Validator::make($request->all(), [
                        'name'=>'required',
                        'bank_id'=>'required',
                    ]);
                    if ($validator->fails()) {
                        return response()->json(['errors'=>$validator->errors()], 400);
                    }
                    $bank_branch = new BankBranch;
                    $bank_branch->package_buy_id = $user['package_buy_id'];
                    $bank_branch->name = $request['name'];
                    $bank_branch->bank_id = $request['bank_id'];
                    $bank_branch->save();
                    if($bank_branch){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Bank Branch Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Bank Branch",
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
    //BankBranchLists
    public function BankBranchLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_branch = BankBranch::with('bank')->where('package_buy_id',$user['package_buy_id'])->select('id','bank_id','name')->orderBy('id','DESC')->paginate(15);
                if($bank_branch){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_branch,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Branch Lists Not Found",
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
    //BankBranch
    public function BankBranch(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_branch = BankBranch::with('bank')->where('package_buy_id',$user['package_buy_id'])->select('id','bank_id','name')->orderBy('id','DESC')->get();
                if($bank_branch){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_branch,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Branch Lists Not Found",
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
    //UpdateBankBranch
    public function UpdateBankBranch(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'bank_id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_branch = BankBranch::findOrFail($request['id']);
        $bank_branch->name = $request['name'];
        $bank_branch->bank_id = $request['bank_id'];
        $bank_branch->save();
        if($bank_branch){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Branch Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteBankBranch
    public function DeleteBankBranch(Request $request){
        $bank_branch = BankBranch::findOrFail($request['id']);
        $bank_branch->delete();
        if($bank_branch){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Branch Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleBankBranch
     public function SingleBankBranch($bank_branch_id){
        $bank_branch = BankBranch::where('id',$bank_branch_id)->select('id','bank_id','name')->first();

        if($bank_branch){
            return response()->json([
                'status'=>true,
                'lists'=> $bank_branch,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateDesignation
    public function CreateDesignation(Request $request){
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
                    $designation = new Designation;
                    $designation->package_buy_id = $user['package_buy_id'];
                    $designation->name = $request['name'];
                    $designation->save();
                    if($designation){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Designation Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Designation",
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
    //DesignationLists
    public function DesignationLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $designation = Designation::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($designation){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $designation,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Designation Lists Not Found",
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
    //Designation
    public function Designation(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $designation = Designation::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($designation){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $designation,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Designation Lists Not Found",
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
    //UpdateDesignation
    public function UpdateDesignation(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $designation = Designation::findOrFail($request['id']);
        $designation->name = $request['name'];
        $designation->save();
        if($designation){
            return response()->json([
                'status'=>true,
                'message'=> "Designation Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteDesignation
    public function DeleteDesignation(Request $request){
        $designation = Designation::findOrFail($request['id']);
        $designation->delete();
        if($designation){
            return response()->json([
                'status'=>true,
                'message'=> "Designation Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleDesignation
     public function SingleDesignation($designation_id){
        $designation = Designation::where('id',$designation_id)->select('id','name')->first();

        if($designation){
            return response()->json([
                'status'=>true,
                'lists'=> $designation,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //CreateBankAccountType
    public function CreateBankAccountType(Request $request){
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
                    $ban_acc_type = new BankAccType;
                    $ban_acc_type->package_buy_id = $user['package_buy_id'];
                    $ban_acc_type->name = $request['name'];
                    $ban_acc_type->save();
                    if($ban_acc_type){
                        return response()->json([
                            'status'=>true,
                            'message'=>"Bank Account Type Created Successfully",
                        ],200);
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>"Something Is Wrong To Create Bank Account Type",
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
    //BankAccountTypeLists
    public function BankAccountTypeLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_acc_type = BankAccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->paginate(15);
                if($bank_acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Account Type Lists Not Found",
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
    //BankAccountType
    public function BankAccountType(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $bank_acc_type = BankAccType::where('package_buy_id',$user['package_buy_id'])->select('id','name')->orderBy('id','DESC')->get();
                if($bank_acc_type){
                    return response()->json([
                        'status'=>true,
                        'lists'=> $bank_acc_type,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Bank Account Type Lists Not Found",
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
    //UpdateBankAccountType
    public function UpdateBankAccountType(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 400);
        }
        $bank_acc_type = BankAccType::findOrFail($request['id']);
        $bank_acc_type->name = $request['name'];
        $bank_acc_type->save();
        if($bank_acc_type){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Account Type Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Update",
            ],200);
        }
    }
    //DeleteBankAccountType
    public function DeleteBankAccountType(Request $request){
        $bank_acc_type = BankAccType::findOrFail($request['id']);
        $bank_acc_type->delete();
        if($bank_acc_type){
            return response()->json([
                'status'=>true,
                'message'=> "Bank Account Type Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Worng To Delete",
            ],200);
        }
    }
     //SingleBankAccountType
     public function SingleBankAccountType($bank_acc_type_id){
        $bank_acc_type = BankAccType::where('id',$bank_acc_type_id)->select('id','name')->first();
        if($bank_acc_type){
            return response()->json([
                'status'=>true,
                'lists'=> $bank_acc_type,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Not Found",
            ],200);
        }
    }
    //sale quotation
    public function SaleQuotation(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'acc_cus_sup_id'=>'required',
                    'product_order_by_id'=>'required',
                    'quotation_sale_details'=>'required',
                    'document'=>'required',
                    'tax_amount'=>'required',
                    'service_charge'=>'required',
                    'shipping_cost'=>'required',
                    'paid_amount'=>'required',
                    'items'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }

                //document
                $image = array();
                foreach ($request->file('document') as $imagefile) {
                    $sale_quotation = mt_rand().'.'.$imagefile->extension();
                    $imagefile->move(public_path('images/sale_quotation'), $sale_quotation);
                    $image[] = $_SERVER['HTTP_HOST'].'/public/images/sale_quotation/'.$sale_quotation;
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_sale_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $invoice = date('y').date('m').date('d').date('i').date('s');
                $sale_quotation = new SaleQuotation;
                $sale_quotation->package_buy_id = $user['package_buy_id'];
                $sale_quotation->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $sale_quotation->quotation_invoice_no = $invoice;
                $sale_quotation->product_order_by_id = $request->product_order_by_id;
                $sale_quotation->quotation_date = date('Y-m-d');
                $sale_quotation->total_sale_amount =  $sub_total;
                $sale_quotation->total_qty =  array_sum(array_column($items, 'qty'));
                $sale_quotation->quotation_sale_details = $request['quotation_sale_details'];
                $sale_quotation->total_tax_amount = $tax_amount;
                $sale_quotation->service_charge = $service_charge;
                $sale_quotation->shipping_cost = $shipping_cost;
                $sale_quotation->grand_total = $total_sale_amount;
                $sale_quotation->paid_amount = $paid_amount;
                $sale_quotation->due_amount = $total_sale_amount - $paid_amount;
                $sale_quotation->document = $doc;
                $sale_quotation->save();
                //sale quotation items
                foreach($items as $item){
                    $product_id = $item->product_id;
                    $unit_id = $item->unit_id;
                    $avg_qty = $item->avg_qty;
                    $bag = $item->bag;
                    $qty = $item->qty;
                    $rate = $item->rate;
                    $amount = $item->amount;
                    $sale_items = new SaleQuotationItem;
                    $sale_items->package_buy_id  = $user['package_buy_id'];
                    $sale_items->sale_quotation_id   = $sale_quotation->id;
                    $sale_items->quotation_invoice_no = $invoice;
                    $sale_items->product_id = $product_id;
                    $sale_items->unit_id = $unit_id;
                    $sale_items->avg_qty = $avg_qty;
                    $sale_items->bag = $bag;
                    $sale_items->qty = $qty;
                    $sale_items->rate = $rate;
                    $sale_items->amount = $amount;
                    $sale_items->save();
                }
                if($sale_quotation && $sale_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Sale Quotation Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Sale Quotation",
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
    //SaleQuotationLists
    public function SaleQuotationLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $sale_quotation = SaleQuotation::where('package_buy_id',$user['package_buy_id'])->select('id','quotation_invoice_no','quotation_date','total_qty','total_sale_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($sale_quotation){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$sale_quotation,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Sale Quotation Lists Not Found",
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
    //SaleQuotationDetails
    public function SaleQuotationDetails(Request $request){
        $sale_quotation = SaleQuotation::with('acc_cus_sup')->where('id',$request['sale_quotation_id'])->select('id','acc_cus_sup_id','quotation_invoice_no','product_order_by_id','quotation_date','total_qty','quotation_sale_details','total_sale_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','document')->first();
        $sale_quotation_items = SaleQuotationItem::with('product','unit')->where('sale_quotation_id',$request['sale_quotation_id'])->select('id','sale_quotation_id','unit_id','quotation_invoice_no','product_id','avg_qty','bag','qty','rate','amount')->get();
        if($sale_quotation && $sale_quotation_items){
            return response()->json([
                'status'=>true,
                'lists'=>$sale_quotation,
                'items'=>$sale_quotation_items
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Sale Quotation Lists Not Found",
            ],200);
        }
    }
    //UpdateSaleQuotation
    public function UpdateSaleQuotation(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){

                //document
                $image = array();
                if(!empty($request->file('document'))){
                    foreach ($request->file('document') as $imagefile) {
                        $sale_quotation = mt_rand().'.'.$imagefile->extension();
                        $imagefile->move(public_path('images/sale_quotation'), $sale_quotation);
                        $image[] = $_SERVER['HTTP_HOST'].'/public/images/sale_quotation/'.$sale_quotation;
                    }
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_sale_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $invoice = date('y').date('m').date('d').date('i').date('s');
                $sale_quotation = SaleQuotation::where('id',$request['id'])->first();
                if(isset($request['acc_cus_sup_id'])){
                    $sale_quotation->acc_cus_sup_id = $request['acc_cus_sup_id'];
                }
                if(isset($request->product_order_by_id)){
                    $sale_quotation->product_order_by_id = $request->product_order_by_id;
                }
                if(isset($sub_total)){
                    $sale_quotation->total_sale_amount =  $sub_total;
                }
                $total_qty = array_sum(array_column($items, 'qty'));
                if($total_qty){
                    $sale_quotation->total_qty =  array_sum(array_column($items, 'qty'));
                }
                if(isset($request['quotation_sale_details'])){
                    $sale_quotation->quotation_sale_details = $request['quotation_sale_details'];
                }
                if(isset($tax_amount)){
                    $sale_quotation->total_tax_amount = $tax_amount;
                }
                if(isset($service_charge)){
                    $sale_quotation->service_charge = $service_charge;
                }
                if(isset($shipping_cost)){
                    $sale_quotation->shipping_cost = $shipping_cost;
                }
                if(isset($total_sale_amount)){
                    $sale_quotation->grand_total = $total_sale_amount;
                }
                if(isset($paid_amount)){
                    $sale_quotation->paid_amount = $paid_amount;
                }
                $due_amount = $total_sale_amount - $paid_amount;
                if(isset($due_amount)){
                    $sale_quotation->due_amount = $due_amount;
                }
                if(isset($doc)){
                    $sale_quotation->document = $doc;
                }
                $sale_quotation->save();
                //sale quotation items
                foreach($items as $value){
                    $sale_items = SaleQuotationItem::where('id',$value->id)->first();
                    if(isset($value->product_id)){
                        $sale_items->product_id = $value->product_id;
                    }
                    if(isset($value->unit_id)){
                        $sale_items->unit_id = $value->unit_id;
                    }
                    if(isset($value->avg_qty)){
                        $sale_items->avg_qty = $value->avg_qty;
                    }
                    if(isset($value->bag)){
                        $sale_items->bag = $value->bag;
                    }
                    if(isset($value->qty)){
                        $sale_items->qty = $value->qty;
                    }
                    if(isset($value->rate)){
                        $sale_items->rate = $value->rate;
                    }
                    if($value->amount){
                        $sale_items->amount = $value->amount;
                    }
                    $sale_items->save();
                }
                if($sale_quotation && $sale_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Sale Quotation Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Updated Sale Quotation",
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
    //SaleQuotationDelete
    public function SaleQuotationDelete(Request $request){
        $sale_quotation = SaleQuotation::where('id',$request['id'])->delete();
        if($sale_quotation){
            return response()->json([
                'status'=>true,
                'message'=>"Sale Quotation Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Sale Quotation",
            ],200);
        }
    }
    //purchase quotation
    public function PurchaseQuotation(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'acc_cus_sup_id'=>'required',
                    'product_order_by_id'=>'required',
                    'quotation_purchase_details'=>'required',
                    'document'=>'required',
                    'tax_amount'=>'required',
                    'service_charge'=>'required',
                    'shipping_cost'=>'required',
                    'paid_amount'=>'required',
                    'items'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                //document
                $image = array();
                foreach ($request->file('document') as $imagefile) {
                    $sale_quotation = mt_rand().'.'.$imagefile->extension();
                    $imagefile->move(public_path('images/purchase_quotation'), $sale_quotation);
                    $image[] = $_SERVER['HTTP_HOST'].'/public/images/purchase_quotation/'.$sale_quotation;
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_purchase_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $invoice = date('y').date('m').date('d').date('i').date('s');
                $pur_quotation = new PurchaseQuotation;
                $pur_quotation->package_buy_id = $user['package_buy_id'];
                $pur_quotation->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $pur_quotation->quotation_invoice_no = $invoice;
                $pur_quotation->product_order_by_id = $request->product_order_by_id;
                $pur_quotation->quotation_date = date('Y-m-d');
                $pur_quotation->total_purchase_amount =  $sub_total;
                $pur_quotation->total_qty =  array_sum(array_column($items, 'qty'));
                $pur_quotation->quotation_purchase_details = $request['quotation_purchase_details'];
                $pur_quotation->total_tax_amount = $tax_amount;
                $pur_quotation->service_charge = $service_charge;
                $pur_quotation->shipping_cost = $shipping_cost;
                $pur_quotation->grand_total = $total_purchase_amount;
                $pur_quotation->paid_amount = $paid_amount;
                $pur_quotation->due_amount = $total_purchase_amount - $paid_amount;
                $pur_quotation->document = $doc;
                $pur_quotation->save();
                //purchase quotation items
                foreach($items as $item){
                    $product_id = $item->product_id;
                    $unit_id = $item->unit_id;
                    $avg_qty = $item->avg_qty;
                    $bag = $item->bag;
                    $qty = $item->qty;
                    $rate = $item->rate;
                    $amount = $item->amount;
                    $pur_items = new PurchaseQuotationItem;
                    $pur_items->package_buy_id  = $user['package_buy_id'];
                    $pur_items->purchase_quotation_id = $pur_quotation->id;
                    $pur_items->quotation_invoice_no = $invoice;
                    $pur_items->product_id = $product_id;
                    $pur_items->unit_id = $unit_id;
                    $pur_items->avg_qty = $avg_qty;
                    $pur_items->bag = $bag;
                    $pur_items->qty = $qty;
                    $pur_items->rate = $rate;
                    $pur_items->amount = $amount;
                    $pur_items->save();
                }
                if($pur_quotation && $pur_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Purchase Quotation Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Purchase Quotation",
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
    //PurchaseQuotationLists
    public function PurchaseQuotationLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $purchase_quotation = PurchaseQuotation::where('package_buy_id',$user['package_buy_id'])->select('id','quotation_invoice_no','quotation_date','total_qty','total_purchase_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($purchase_quotation){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$purchase_quotation,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Purchase Quotation Lists Not Found",
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
    //PurchaseQuotationDetails
    public function PurchaseQuotationDetails(Request $request){
        $purchase_quotation = PurchaseQuotation::with('acc_cus_sup')->where('id',$request['purchase_quotation_id'])->select('id','acc_cus_sup_id','quotation_invoice_no','product_order_by_id','quotation_date','total_qty','quotation_purchase_details','total_purchase_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','document')->first();
        $purchase_quotation_items = PurchaseQuotationItem::with('product','unit')->where('purchase_quotation_id',$request['purchase_quotation_id'])->select('id','purchase_quotation_id','unit_id','quotation_invoice_no','product_id','avg_qty','bag','qty','rate','amount')->get();
        if($purchase_quotation && $purchase_quotation_items){
            return response()->json([
                'status'=>true,
                'lists'=>$purchase_quotation,
                'items'=>$purchase_quotation_items
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Purchase Quotation Lists Not Found",
            ],200);
        }
    }
    //UpdatePurchaseQuotation
    public function UpdatePurchaseQuotation(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                //document
                $image = array();
                if(!empty($request->file('document'))){
                    foreach ($request->file('document') as $imagefile) {
                        $sale_quotation = mt_rand().'.'.$imagefile->extension();
                        $imagefile->move(public_path('images/purchase_quotation'), $sale_quotation);
                        $image[] = $_SERVER['HTTP_HOST'].'/public/images/purchase_quotation/'.$sale_quotation;
                    }
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_purchase_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $pur_quotation = PurchaseQuotation::where('id',$request['id'])->first();
                if(isset($request['acc_cus_sup_id'])){
                    $pur_quotation->acc_cus_sup_id = $request['acc_cus_sup_id'];
                }
                if(isset($request->product_order_by_id)){
                    $pur_quotation->product_order_by_id = $request->product_order_by_id;
                }
                if(isset($sub_total)){
                    $pur_quotation->total_purchase_amount =  $sub_total;
                }
                $total_qty = array_sum(array_column($items, 'qty'));
                if(isset($total_qty)){
                    $pur_quotation->total_qty =  $total_qty;
                }
                if(isset($request['quotation_purchase_details'])){
                    $pur_quotation->quotation_purchase_details = $request['quotation_purchase_details'];
                }
                if(isset($tax_amount)){
                    $pur_quotation->total_tax_amount = $tax_amount;
                }
                if(isset($service_charge)){
                    $pur_quotation->service_charge = $service_charge;
                }
                if(isset($shipping_cost)){
                    $pur_quotation->shipping_cost = $shipping_cost;
                }
                if(isset($total_purchase_amount)){
                    $pur_quotation->grand_total = $total_purchase_amount;
                }
                if(isset($paid_amount)){
                    $pur_quotation->paid_amount = $paid_amount;
                }
                $due = $total_purchase_amount - $paid_amount;
                if(isset($due)){
                    $pur_quotation->due_amount = $total_purchase_amount - $paid_amount;
                }

                if(isset($doc)){
                    $pur_quotation->document = $doc;
                }
                $pur_quotation->save();
                //purchase quotation items
                foreach($items as $value){
                    $pur_itmes = PurchaseQuotationItem::where('id',$value->id)->first();
                    if(isset($value->product_id)){
                        $pur_itmes->product_id = $value->product_id;
                    }
                    if(isset($value->unit_id)){
                        $pur_itmes->unit_id = $value->unit_id;
                    }
                    if(isset($value->avg_qty)){
                        $pur_itmes->avg_qty = $value->avg_qty;
                    }
                    if(isset($value->bag)){
                        $pur_itmes->bag = $value->bag;
                    }
                    if(isset($value->qty)){
                        $pur_itmes->qty = $value->qty;
                    }
                    if(isset($value->rate)){
                        $pur_itmes->rate = $value->rate;
                    }
                    if(isset($value->amount)){
                        $pur_itmes->amount = $value->amount;
                    }
                    $pur_itmes->save();
                }
                if($pur_quotation && $pur_itmes){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Purchase Quotation Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Updated Purchase Quotation",
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
    //PurchaseQuotationDelete
    public function PurchaseQuotationDelete(Request $request){
        $pur_quotation = PurchaseQuotation::where('id',$request['id'])->delete();
        if($pur_quotation){
            return response()->json([
                'status'=>true,
                'message'=>"Purchase Quotation Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Purchase Quotation",
            ],200);
        }
    }
    //purchase
    public function Purchase(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'acc_cus_sup_id'=>'required',
                    'product_order_by_id'=>'required',
                    'purchase_details'=>'required',
                    'document'=>'required',
                    'tax_amount'=>'required',
                    'service_charge'=>'required',
                    'shipping_cost'=>'required',
                    'paid_amount'=>'required',
                    'items'=>'required',
                    'payment_method_id'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                //document
                $image = array();
                foreach ($request->file('document') as $imagefile) {
                    $purchase = mt_rand().'.'.$imagefile->extension();
                    $imagefile->move(public_path('images/purchase'), $purchase);
                    $image[] = $_SERVER['HTTP_HOST'].'/public/images/purchase/'.$purchase;
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_purchase_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $invoice = date('y').date('m').date('d').date('i').date('s');
                $purchase = new Purchase;
                $purchase->package_buy_id = $user['package_buy_id'];
                $purchase->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $purchase->purchase_invoice_no = $invoice;
                $purchase->product_order_by_id = $request->product_order_by_id;
                $purchase->purchase_date = date('Y-m-d');
                $purchase->total_purchase_amount =  $sub_total;
                $purchase->total_qty =  array_sum(array_column($items, 'qty'));
                $purchase->purchase_details = $request['purchase_details'];
                $purchase->tax_amount = $request->tax_amount;
                $purchase->total_tax_amount = $tax_amount;
                $purchase->service_charge = $service_charge;
                $purchase->shipping_cost = $shipping_cost;
                $purchase->grand_total = $total_purchase_amount;
                $purchase->paid_amount = $paid_amount;
                $purchase->due_amount = $total_purchase_amount - $paid_amount;
                $purchase->document = $doc;
                $purchase->payment_method_id = $request['payment_method_id'];
                $purchase->save();
                //purchase items
                foreach($items as $item){
                    $product_id = $item->product_id;
                    $unit_id = $item->unit_id;
                    $avg_qty = $item->avg_qty;
                    $bag = $item->bag;
                    $qty = $item->qty;
                    $rate = $item->rate;
                    $amount = $item->amount;
                    $pur_items = new PurchaseItem;
                    $pur_items->package_buy_id  = $user['package_buy_id'];
                    $pur_items->purchase_id = $purchase->id;
                    $pur_items->purchase_invoice_no = $invoice;
                    $pur_items->product_id = $product_id;
                    $pur_items->unit_id = $unit_id;
                    $pur_items->avg_qty = $avg_qty;
                    $pur_items->bag = $bag;
                    $pur_items->qty = $qty;
                    $pur_items->rate = $rate;
                    $pur_items->amount = $amount;
                    $pur_items->save();
                }
                if($purchase && $pur_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Purchase Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Purchase",
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
    //PurchaseLists
    public function PurchaseLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $purchase = Purchase::where('package_buy_id',$user['package_buy_id'])->select('id','purchase_invoice_no','receipt_invoice_no','challan_invoice_no','purchase_date','total_qty','total_purchase_amount','tax_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($purchase){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$purchase,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Purchase Lists Not Found",
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
    //PurchaseDetails
    public function PurchaseDetails(Request $request){
        $purchase = Purchase::with('acc_cus_sup','payment_method')->where('purchase_invoice_no',$request['purchase_invoice_no'])->select('id','acc_cus_sup_id','purchase_invoice_no','receipt_invoice_no','challan_invoice_no','product_order_by_id','purchase_date','total_qty','purchase_details','total_purchase_amount','tax_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','document','payment_method_id')->first();
        $purchase_items = PurchaseItem::with('product','unit')->where('purchase_invoice_no',$request['purchase_invoice_no'])->select('id','purchase_id','unit_id','purchase_invoice_no','product_id','avg_qty','bag','qty','rate','amount')->get();
        if($purchase && $purchase_items){
            return response()->json([
                'status'=>true,
                'lists'=>$purchase,
                'items'=>$purchase_items
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Purchase Quotation Lists Not Found",
            ],200);
        }
    }
    //UpdatePurchase
    public function UpdatePurchase(Request $request){

        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                //document
                $image = array();
                if(!empty($request->file('document'))){
                    if($request->file('document')){
                        foreach ($request->file('document') as $imagefile) {
                            $purchase = mt_rand().'.'.$imagefile->extension();
                            $imagefile->move(public_path('images/purchase'), $purchase);
                            $image[] = $_SERVER['HTTP_HOST'].'/public/images/purchase/'.$purchase;
                        }
                    }
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items,'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_purchase_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $purchase = Purchase::where('purchase_invoice_no',$request['purchase_invoice_no'])->first();
                if(isset($request['acc_cus_sup_id'])){
                    $purchase->acc_cus_sup_id = $request['acc_cus_sup_id'];
                }
                if(isset($request->product_order_by_id)){
                    $purchase->product_order_by_id = $request->product_order_by_id;
                }
                if(isset($sub_total)){
                    $purchase->total_purchase_amount =  $sub_total;
                }
                $total_qty = array_sum(array_column($items, 'qty'));
                if(isset($total_qty)){
                    $purchase->total_qty = $total_qty;
                }
                if(isset($request['purchase_details'])){
                    $purchase->purchase_details = $request['purchase_details'];
                }
                if(isset($request->tax)){
                    $purchase->tax_amount = $request->tax_amount;
                }
                if(isset($tax_amount) >0){
                    $purchase->total_tax_amount = $tax_amount;
                }
                if(isset($service_charge)){
                    $purchase->service_charge = $service_charge;
                }
                if(isset($shipping_cost)){
                    $purchase->shipping_cost = $shipping_cost;
                }
                if(isset($total_purchase_amount)){
                    $purchase->grand_total = $total_purchase_amount;
                }
                if(isset($paid_amount)){
                    $purchase->paid_amount = $paid_amount;
                }
                $due_amount = $total_purchase_amount - $paid_amount;
                if(isset($due_amount)){
                    $purchase->due_amount = $due_amount;
                }
                if(isset($request['payment_method_id'])){
                    $purchase->payment_method_id = $request['payment_method_id'];
                }
                if(isset($doc)){
                    $purchase->document = $doc;
                }
                $purchase->save();
                //purchase items
                foreach($items as $value){
                    $pur_itmes = PurchaseItem::where('id',$value->id)->first();
                    if(isset($value->product_id)){
                        $pur_itmes->product_id = $value->product_id;
                    }
                    if(isset($value->unit_id)){
                        $pur_itmes->unit_id = $value->unit_id;
                    }
                    if(isset($value->avg_qty)){
                        $pur_itmes->avg_qty = $value->avg_qty;
                    }
                    if(isset($value->bag)){
                        $pur_itmes->bag = $value->bag;
                    }

                    if(isset($value->qty)){
                        $pur_itmes->qty = $value->qty;
                    }
                    if(isset($value->rate)){
                        $pur_itmes->rate = $value->rate;
                    }
                    if(isset($value->amount)){
                        $pur_itmes->amount = $value->amount;
                    }
                    $pur_itmes->save();

                }
                if($purchase && $pur_itmes){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Purchase Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Updated Purchase",
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
    //PurchaseDelete
    public function PurchaseDelete(Request $request){
        $purchase_invoice = Purchase::where('id',$request['id'])->select('purchase_invoice_no')->first();
        $purchase_challan = ReceiptChallan::where('purchase_invoice_no',$purchase_invoice['purchase_invoice_no'])->delete();
        $purchase = Purchase::where('id',$request['id'])->delete();
        if($purchase){
            return response()->json([
                'status'=>true,
                'message'=>"Purchase Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Purchase",
            ],200);
        }
    }
    //sale
    public function Sale(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'acc_cus_sup_id'=>'required',
                    'product_order_by_id'=>'required',
                    'sale_details'=>'required',
                    'document'=>'required',
                    'tax_amount'=>'required',
                    'service_charge'=>'required',
                    'shipping_cost'=>'required',
                    'paid_amount'=>'required',
                    'items'=>'required',
                    'payment_method_id'=>'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                //document
                $image = array();
                foreach ($request->file('document') as $imagefile) {
                    $sale = mt_rand().'.'.$imagefile->extension();
                    $imagefile->move(public_path('images/sale'), $sale);
                    $image[] = $_SERVER['HTTP_HOST'].'/public/images/sale/'.$sale;
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_sale_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $invoice = date('y').date('m').date('d').date('i').date('s');
                $sale = new Sale;
                $sale->package_buy_id = $user['package_buy_id'];
                $sale->acc_cus_sup_id = $request['acc_cus_sup_id'];
                $sale->sale_invoice_no = $invoice;
                $sale->product_order_by_id = $request->product_order_by_id;
                $sale->sale_date = date('Y-m-d');
                $sale->total_sale_amount =  $sub_total;
                $sale->total_qty = array_sum(array_column($items, 'qty'));
                $sale->sale_details = $request['sale_details'];
                $sale->tax_amount = $request->tax_amount;
                $sale->total_tax_amount = $tax_amount;
                $sale->service_charge = $service_charge;
                $sale->shipping_cost = $shipping_cost;
                $sale->grand_total = $total_sale_amount;
                $sale->paid_amount = $paid_amount;
                $sale->due_amount = $total_sale_amount - $paid_amount;
                $sale->document = $doc;
                $sale->payment_method_id = $request['payment_method_id'];
                $sale->save();
                //sale items
                foreach($items as $item){
                    $product_id = $item->product_id;
                    $unit_id = $item->unit_id;
                    $avg_qty = $item->avg_qty;
                    $bag = $item->bag;
                    $qty = $item->qty;
                    $rate = $item->rate;
                    $amount = $item->amount;
                    $sale_items = new SaleItem;
                    $sale_items->package_buy_id  = $user['package_buy_id'];
                    $sale_items->sale_id = $sale->id;
                    $sale_items->sale_invoice_no = $invoice;
                    $sale_items->product_id = $product_id;
                    $sale_items->unit_id = $unit_id;
                    $sale_items->avg_qty = $avg_qty;
                    $sale_items->bag = $bag;
                    $sale_items->qty = $qty;
                    $sale_items->rate = $rate;
                    $sale_items->amount = $amount;
                    $sale_items->save();
                }
                if($sale && $sale_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Sale Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Sale",
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
    //SaleLists
    public function SaleLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $sale = Sale::where('package_buy_id',$user['package_buy_id'])->select('id','sale_invoice_no','delivery_invoice_no','challan_invoice_no','sale_date','total_qty','total_sale_amount','tax_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($sale){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$sale,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Sale Lists Not Found",
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
    //SaleDetails
    public function SaleDetails(Request $request){
        $sale = Sale::with('acc_cus_sup','payment_method')->where('sale_invoice_no',$request['sale_invoice_no'])->select('id','acc_cus_sup_id','sale_invoice_no','delivery_invoice_no','challan_invoice_no','product_order_by_id','sale_date','total_qty','sale_details','total_sale_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','document','payment_method_id')->first();
        $sale_items = SaleItem::with('product','unit')->where('sale_invoice_no',$request['sale_invoice_no'])->select('id','sale_id','unit_id','sale_invoice_no','product_id','avg_qty','bag','qty','rate','amount')->get();
        if($sale && $sale_items){
            return response()->json([
                'status'=>true,
                'lists'=>$sale,
                'items'=>$sale_items
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Sale Lists Not Found",
            ],200);
        }
    }
    //UpdateSale
    public function UpdateSale(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                //document
                $image = array();
                if(!empty($request->file('document'))){
                    foreach ($request->file('document') as $imagefile) {
                        $sale = mt_rand().'.'.$imagefile->extension();
                        $imagefile->move(public_path('images/sale'), $sale);
                        $image[] = $_SERVER['HTTP_HOST'].'/public/images/sale/'.$sale;
                    }
                }
                $doc = json_encode($image);
                $items = json_decode($request->items);
                $service_charge = $request->service_charge;
                $shipping_cost = $request->shipping_cost;
                $paid_amount = $request->paid_amount;
                $sub_total = array_sum(array_column($items, 'amount'));
                $tax_amount = ($request->tax_amount / 100) * $sub_total;
                $total_sale_amount = $sub_total + $tax_amount + $service_charge + $shipping_cost;
                $sale = Sale::where('sale_invoice_no',$request['sale_invoice_no'])->first();
                if(isset($request['acc_cus_sup_id'])){
                    $sale->acc_cus_sup_id = $request['acc_cus_sup_id'];
                }
                if(isset($request->product_order_by_id)){
                    $sale->product_order_by_id = $request->product_order_by_id;
                }
                if(isset($sub_total)){
                    $sale->total_sale_amount =  $sub_total;
                }
                $total_qty = array_sum(array_column($items, 'qty'));
                if(isset($total_qty) > 0){
                    $sale->total_qty =  array_sum(array_column($items, 'qty'));
                }
                if(isset($request['sale_details'])){
                    $sale->sale_details = $request['sale_details'];
                }
                if(isset($request->tax_amount)){
                    $sale->tax_amount = $request->tax_amount;
                }
                if(isset($tax_amount) > 0){
                    $sale->total_tax_amount = $tax_amount;
                }
                if(isset($service_charge)){
                    $sale->service_charge = $service_charge;
                }
                if(isset($shipping_cost)){
                    $sale->shipping_cost = $shipping_cost;
                }
                if(isset($total_sale_amount)){
                    $sale->grand_total = $total_sale_amount;
                }
                if(isset($paid_amount)){
                    $sale->paid_amount = $paid_amount;
                }
                $due = $total_sale_amount - $paid_amount;
                if(isset($due)){
                    $sale->due_amount = $total_sale_amount - $paid_amount;
                }
                if(isset($request['payment_method_id'])){
                    $sale->payment_method_id = $request['payment_method_id'];
                }
                if(isset($doc)){
                    $sale->document = $doc;
                }
                if(isset($request['payment_method_id'])){
                    $sale->payment_method_id = $request['payment_method_id'];
                }
                $sale->save();
                //sale items
                foreach($items as $value){
                    if(isset($value->product_id)){
                        $product_id = $value->product_id;
                    }
                    $sale_items = SaleItem::where('id',$value->id)->first();
                    if(isset($product_id)){
                        $sale_items->product_id = $product_id;
                    }
                    if(isset($value->unit_id)){
                        $sale_items->unit_id = $value->unit_id;
                    }
                    if(isset($value->avg_qty)){
                        $sale_items->avg_qty = $value->avg_qty;
                    }
                    if(isset($value->bag)){
                        $sale_items->bag = $value->bag;
                    }
                    if(isset($value->qty)){
                        $sale_items->qty = $value->qty;
                    }
                    if(isset($value->rate)){
                        $sale_items->rate = $value->rate;
                    }
                    if(isset($value->amount)){
                        $sale_items->amount = $value->amount;
                    }
                    $sale_items->save();
                }
                if($sale && $sale_items){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Sale Updated Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Updated Sale",
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
    //SaleDelete
    public function SaleDelete(Request $request){
        $sale_invoice = Sale::where('id',$request['id'])->select('sale_invoice_no')->first();
        $sale_challan = DeliveryChallan::where('sale_invoice_no',$sale_invoice['sale_invoice_no'])->delete();
        $sale = Sale::where('id',$request['id'])->delete();
        if($sale){
            return response()->json([
                'status'=>true,
                'message'=>"Sale Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Sale",
            ],200);
        }
    }
    //create receipt
    public function CreateReceipt(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'purchase_id'=>'required',
                    'ware_house_id'=>'required',
                    'vehicale_id'=>'required',
                    'receipt_details'=>'required',
                    'items'=>'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                $items = json_decode($request->items);
                $receipt = new Receipt;
                $receipt->package_buy_id = $user['package_buy_id'];
                $receipt->purchase_id  = $request['purchase_id'];
                $receipt->receipt_invoice_no  = date('y').date('m').date('d').date('i').date('s');
                $receipt->ware_house_id = $request['ware_house_id'];
                $receipt->vehicale_id  = $request['vehicale_id'];
                $receipt->receipt_details  = $request['receipt_details'];
                $receipt->total_qty  = array_sum(array_column($items, 'order'));
                $receipt->total_receipt  = array_sum(array_column($items, 'receipt'));
                $receipt->total_pending  = array_sum(array_column($items, 'pending'));
                $receipt->receipt_date  = date('Y-m-d');
                $receipt->save();
                $receipt_id = $receipt->id;
                $receipt_invoice_no = $receipt->receipt_invoice_no;
                //receipt items
                foreach($items as $value){
                    $purchase_item_id = $value->id;
                    $order = $value->order;
                    $receipt = $value->receipt;
                    $pending = $value->pending;
                    $receipt_item = new ReceiptItem;
                    $receipt_item->package_buy_id = $user['package_buy_id'];
                    $receipt_item->purchase_id = $request['purchase_id'];
                    $receipt_item->receipt_id = $receipt_id;
                    $receipt_item->receipt_invoice_no = $receipt_invoice_no;
                    $receipt_item->purchase_item_id = $purchase_item_id;
                    $receipt_item->order = $order;
                    $receipt_item->receipt = $receipt;
                    $receipt_item->pending = $pending;
                    $receipt_item->save();
                    //receipt history
                    $receipt_his = new ReceiptHistory;
                    $receipt_his->package_buy_id = $user['package_buy_id'];
                    $receipt_his->purchase_id = $request['purchase_id'];
                    $receipt_his->receipt_id = $receipt_id;
                    $receipt_his->receipt_item_id = $receipt_item->id;
                    $receipt_his->purchase_item_id = $purchase_item_id;
                    $receipt_his->order = $order;
                    $receipt_his->receipt = $receipt;
                    $receipt_his->pending = $pending;
                    $receipt_his->save();
                }
                //purchase module update in receipt invoice no
                $purchase = Purchase::findOrFail($request['purchase_id']);
                $purchase->receipt_invoice_no = $receipt_invoice_no;
                $purchase->save();
                if($receipt && $receipt_item && $purchase){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Receipt Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Receipt",
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
    //PendingReceipt
    public function PendingReceipt(Request $request){
       $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $pending_receipt = Purchase::where('package_buy_id',$user['package_buy_id'])->whereNull('receipt_invoice_no')->select('id','purchase_invoice_no','purchase_date','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($pending_receipt){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$pending_receipt,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Pending Receipt Lists Not Found",
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
    //ReceiptLists
    public function ReceiptLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $receipt = Receipt::with('purchase_invoice_no')->where(['package_buy_id'=>$user['package_buy_id']])->select('id','purchase_id','receipt_invoice_no','total_qty','total_receipt','total_pending','receipt_date','status')->orderBy('id','DESC')->paginate(15);
                if($receipt){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$receipt,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Receipt List Not Found",
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
    //ReceiptDetails
    public function ReceiptDetails(Request $request){
        $receipt = Receipt::with('purchase','warehouse','vehicale')->where('id',$request['receipt_id'])->select('id','purchase_id','receipt_invoice_no','ware_house_id','vehicale_id','receipt_details','total_receipt','total_pending','receipt_date','status')->first();
        $items = ReceiptItem::with('receipt_item')->where('receipt_id',$request['receipt_id'])->select('id','purchase_item_id','receipt_id','order','receipt','pending')->get();
        if($receipt && $items){
            return response()->json([
                'status'=>true,
                'lists'=>$receipt,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Receipt Details Not Found",
            ],200);
        }
    }
    //ReceiptAdd
    public function ReceiptAdd(Request $request){
        $items = json_decode($request->items);
        $add = array_sum(array_column($items, 'add'));

        $receipt = Receipt::where('receipt_invoice_no',$request['receipt_invoice_no'])->first();
        $total_receipt = $receipt['total_receipt'] + $add;
        $total_pending = $receipt['total_pending'] - $add;

        if($receipt['status'] == "accept"){
            return response()->json([
                'status'=>false,
                'message'=>"Receipt Already Complete",
            ],200);
        }elseif($receipt['total_qty'] < $total_receipt){
            return response()->json([
                'status'=>false,
                'message'=>"Sorry! You Are Adding More Qty",
            ],200);
        }elseif($receipt['total_qty'] == $total_receipt){
            $receipt->total_receipt = $total_receipt;
            $receipt->total_pending = $total_pending;
            $receipt->status = 'accept';
            $receipt->save();
        }else{
            $receipt->total_receipt = $total_receipt;
            $receipt->total_pending = $total_pending;
            $receipt->save();
        }
        //item updating
        foreach($items as $value){
            $items = ReceiptItem::where('id',$value->id)->first();
            $items_receipt = $items['receipt'] + $value->add;
            $items_pending = $items['pending'] - $value->add;
            $items->receipt = $items_receipt;
            $items->pending = $items_pending;
            $items->save();
            //history
            $history = new ReceiptHistory;
            $history->package_buy_id = $receipt['package_buy_id'];
            $history->purchase_id = $receipt['purchase_id'];
            $history->receipt_id = $receipt['id'];
            $history->receipt_item_id = $items->id;
            $history->purchase_item_id = $items->purchase_item_id;
            $history->order = $items->order;
            $history->receipt = $value->add;
            $history->pending = $items->order - $value->add;
            $history->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>"Receipt Added Successfully",
        ],200);
    }
    //ReceiptUpdate
    public function ReceiptUpdate(Request $request){
        $items = json_decode($request['items']);
        $receipt = Receipt::where('receipt_invoice_no',$request['receipt_invoice_no'])->first();
        if(isset($request['ware_house_id'])){
            $receipt->ware_house_id = $request['ware_house_id'];
        }
        if(isset($request['vehicale_id'])){
            $receipt->vehicale_id = $request['vehicale_id'];
        }
        if(isset($request['receipt_details'])){
            $receipt->receipt_details = $request['receipt_details'];
        }
        if(isset($request['receipt_details'])){
            $receipt->total_qty = $request['receipt_details'];
        }
        $total_qty = array_sum(array_column($items, 'order'));
        if(isset($total_qty)){
            $receipt->total_qty  = array_sum(array_column($items, 'order'));
        }
        $total_reciept = array_sum(array_column($items, 'receipt'));
        if(isset($total_reciept)){
            $receipt->total_receipt  = array_sum(array_column($items, 'receipt'));
        }
        $total_pending = array_sum(array_column($items, 'pending'));
        if(isset($total_pending)){
            $receipt->total_pending  = array_sum(array_column($items, 'pending'));
        }
        $receipt->save();
        foreach($items as $value){
            $receipt_item = ReceiptItem::where('id',$value->id)->first();
            if(isset($value->order)){
                $receipt_item->order = $value->order;
            }
            if(isset($value->receipt)){
                $receipt_item->receipt = $value->receipt;
            }
            if(isset($value->pending)){
                $receipt_item->pending = $value->pending;
            }
            $receipt_item->save();
            //receipt history
            $receipt_his = ReceiptHistory::where('receipt_item_id',$value->id)->first();
            if(isset($value->order)){
                $receipt_his->order = $value->order;
            }
            if(isset($value->receipt)){
                $receipt_his->receipt = $value->receipt;
            }
            if(isset($value->pending)){
                $receipt_his->pending = $value->pending;
            }
            $receipt_his->save();
        }
        if($receipt && $receipt_item){
            return response()->json([
                'status'=>true,
                'message'=>"Receipt Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Update Receipt",
            ],200);
        }
    }
    //ReceiptInvoice
    public function ReceiptInvoice(Request $request){
        $receipt = Receipt::with('purchase','warehouse','vehicale')->where('receipt_invoice_no',$request['receipt_invoice_no'])->select('id','purchase_id','receipt_invoice_no','ware_house_id','vehicale_id','receipt_details','total_receipt','total_pending','receipt_date','status')->first();
        $items = ReceiptItem::with('receipt_item')->where('receipt_invoice_no',$request['receipt_invoice_no'])->select('id','purchase_item_id','receipt_id','order','receipt','pending')->get();
        if($receipt && $items){
            return response()->json([
                'status'=>true,
                'lists'=>$receipt,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Receipt Invoice Not Found",
            ],200);
        }
    }
    //ReceiptDelete
    public function ReceiptDelete(Request $request){
        $receipt = Receipt::where('id',$request['id'])->select('receipt_invoice_no')->first();
        $receipt_challan = ReceiptChallan::where('receipt_invoice_no',$receipt['receipt_invoice_no'])->delete();
        $receipt = Receipt::where('id',$request['id'])->delete();
        if($receipt){
            return response()->json([
                'status'=>true,
                'message'=>"Receipt Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Receipt",
            ],200);
        }
    }
    //CreateReceiptChallan
    public function CreateReceiptChallan(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'receipt_invoice_no'=>'required',
                    'vehicale_id'=>'required',
                    'challan_details'=>'required',
                    'items'=>'required',
                    // 'document'=>'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                //document
                if($request->document){
                    $challan = time().'.'.$request->document->extension();
                    $request->document->move(public_path('images/receipt_challan'), $challan);
                }
                $items = json_decode($request['items']);
                //purchase_invoice_no
                $purchase_invoice_no = Purchase::where('receipt_invoice_no',$request['receipt_invoice_no'])->select('purchase_invoice_no')->first();
                $receipt = Receipt::where('receipt_invoice_no',$request['receipt_invoice_no'])->first();
                $receipt_challan = new ReceiptChallan;
                $receipt_challan->package_buy_id = $user['package_buy_id'];
                $receipt_challan->purchase_invoice_no = $purchase_invoice_no['purchase_invoice_no'];
                $receipt_challan->receipt_invoice_no = $request['receipt_invoice_no'];
                $receipt_challan->vehicale_id = $request['vehicale_id'];
                $receipt_challan->receipt_challan_invoice_no = date('y').date('m').date('d').date('i').date('s');
                $receipt_challan->challan_details = $request['challan_details'];
                $receipt_challan->total_qty = $receipt['total_qty'];
                $receipt_challan->total_receipt = $receipt['total_receipt'];
                $receipt_challan->total_pending = $receipt['total_pending'];
                $receipt_challan->challan_date = date('Y-m-d');
                if($challan){
                    $receipt_challan->document = $_SERVER['HTTP_HOST'].'/public/images/receipt_challan/'.$challan;
                }
                $receipt_challan->save();
                $receipt_challan_id = $receipt_challan->id;
                $receipt_challan_invoice = $receipt_challan->receipt_challan_invoice_no;
                foreach($items as $value){
                    $receipt_item_id = $value->id;
                    $itm = ReceiptItem::with('receipt_item')->where('id',$receipt_item_id)->first();
                    $receipt_item = new ReceiptChallanItem;
                    $receipt_item->package_buy_id = $user['package_buy_id'];
                    $receipt_item->purchase_invoice_no = $purchase_invoice_no['purchase_invoice_no'];
                    $receipt_item->receipt_invoice_no = $request['receipt_invoice_no'];
                    $receipt_item->receipt_challan_invoice_no  = $receipt_challan_invoice;
                    $receipt_item->receipt_challan_id  = $receipt_challan_id;
                    $receipt_item->receipt_item_id = $receipt_item_id;
                    $receipt_item->unit_id = $itm['receipt_item']['unit_id'];
                    $receipt_item->product_id = $itm['receipt_item']['product_id'];
                    $receipt_item->avg_qty = $itm['receipt_item']['avg_qty'];
                    $receipt_item->bag = $itm['receipt_item']['bag'];
                    $receipt_item->qty = $itm['receipt_item']['qty'];
                    $receipt_item->rate = $itm['receipt_item']['rate'];
                    $receipt_item->amount = $itm['receipt_item']['amount'];
                    $receipt_item->order = $itm['order'];
                    $receipt_item->receipt = $itm['receipt'];
                    $receipt_item->pending = $itm['pending'];
                    $receipt_item->save();
                    //stock
                    $product = Product::where('id',$itm['receipt_item']['product_id'])->first();
                    $stock = Stock::where('product_id',$itm['receipt_item']['product_id'])->where('warehouse_id',$receipt['ware_house_id'])->first();
                    if($stock){
                        $stock->stock_old = $stock['stock_now'];
                        $stock->stock_now = $stock['stock_now'] + $itm['receipt'];
                        $stock->date = date('Y-m-d');
                        $stock->save();
                    }else{
                        $stock = new Stock;
                        $stock->package_buy_id = $user['package_buy_id'];
                        $stock->product_id = $itm['receipt_item']['product_id'];
                        $stock->warehouse_id = $receipt['ware_house_id'];
                        $stock->stock_now = $itm['receipt_item']['qty'];
                        $stock->stock_old = $itm['receipt_item']['qty'];
                        $stock->sale_price = $product['our_price'];
                        $stock->purchase_price = $product['supplier_price'];
                        $stock->production_price = null;
                        $stock->production_qty = null;
                        $stock->date = date('Y-m-d');
                        $stock->save();
                    }
                    //stock history
                    $stock_history = new StockHistory;
                    $stock_history->package_buy_id = $user['package_buy_id'];
                    $stock_history->stock_id = $stock->id;
                    $stock_history->product_id = $itm['receipt_item']['product_id'];
                    $stock_history->stock = $itm['receipt'];
                    $stock_history->date = date('Y-m-d');
                    $stock_history->stock_type = 'in';
                    $stock_history->save();
                }
                //challan update in purchase table with challan invoice no
                $purchase_id = Receipt::where('receipt_invoice_no',$request['receipt_invoice_no'])->select('purchase_id')->first();
                $purchase = Purchase::where('id',$purchase_id['purchase_id'])->first();
                $purchase->challan_invoice_no = $receipt_challan_invoice;
                $purchase->save();
                if($receipt_challan && $receipt_item){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Receipt Challan Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Receipt Challan",
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
    //ReceiptChallanLists
    public function ReceiptChallanLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $receipt_challan = ReceiptChallan::where(['package_buy_id'=>$user['package_buy_id']])->select('id','purchase_invoice_no','receipt_invoice_no','vehicale_id','receipt_challan_invoice_no','challan_details','total_qty','total_receipt','total_pending','challan_date','document','status')->orderBy('id','DESC')->paginate(15);
                if($receipt_challan){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$receipt_challan,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Receipt Challan List Not Found",
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
    //ReceiptChallanDetails
    public function ReceiptChallanDetails(Request $request){
        $receipt_challan = ReceiptChallan::with('vehicale')->where('receipt_challan_invoice_no',$request['receipt_challan_invoice_no'])->select('id','purchase_invoice_no','receipt_invoice_no','receipt_challan_invoice_no','vehicale_id','challan_details','total_qty','total_receipt','total_pending','challan_date','document','status')->get();
        $customer_id = Purchase::where('challan_invoice_no',$request['receipt_challan_invoice_no'])->select('id','acc_cus_sup_id')->first();
        $customer = AccCustomerSupplier::where('id',$customer_id['acc_cus_sup_id'])->select('id','acc_name','email','phone','address','acc_opening_balance','acc_hold_balance','profile_image')->first();
        $items = ReceiptChallanItem::with('product','unit')->where('receipt_challan_invoice_no',$request['receipt_challan_invoice_no'])->select('id','unit_id','product_id','avg_qty','bag','qty','rate','amount','order','receipt','pending')->get();
        if($receipt_challan && $items){
            return response()->json([
                'status'=>true,
                'customer'=>$customer,
                'lists'=>$receipt_challan,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Receipt Challan Details Not Found",
            ],200);
        }
    }
    //ReceiptChallanDelete
    public function ReceiptChallanDelete(Request $request){
        $receipt_challan = ReceiptChallan::where('id',$request['id'])->delete();
        if($receipt_challan){
            return response()->json([
                'status'=>true,
                'message'=>"Receipt Challan Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Receipt Challan",
            ],200);
        }
    }
    //ReceiptChallanUpdate
    public function ReceiptChallanUpdate(Request $request){
        $receipt_challan = ReceiptChallan::where('receipt_challan_invoice_no',$request['receipt_challan_invoice_no'])->first();
        //document
        if($request->document){
            $image_name = basename($receipt_challan['document']);
            File::delete(public_path("images/receipt_challan/".$image_name));
            $challan = time().'.'.$request->document->extension();
            $request->document->move(public_path('images/receipt_challan'), $challan);
        }
        if(isset($request['vehicale_id'])){
            $receipt_challan->vehicale_id = $request['vehicale_id'];
        }
        if(isset($request['challan_details'])){
            $receipt_challan->challan_details = $request['challan_details'];
        }
        if(isset($challan)){
            $receipt_challan->document = $_SERVER['HTTP_HOST'].'/public/images/receipt_challan/'.$challan;
        }
        $receipt_challan->save();
        if($receipt_challan){
            return response()->json([
                'status'=>true,
                'message'=>"Receipt Challan Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Update Receipt Challan",
            ],200);
        }
    }
    //CreateDelivery
    public function CreateDelivery(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'sale_id'=>'required',
                    'ware_house_id'=>'required',
                    'vehicale_id'=>'required',
                    'delivery_details'=>'required',
                    'items'=>'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                $items = json_decode($request->items);
                $delivery = new Delivery;
                $delivery->package_buy_id = $user['package_buy_id'];
                $delivery->sale_id  = $request['sale_id'];
                $delivery->delivery_invoice_no  = date('y').date('m').date('d').date('i').date('s');
                $delivery->ware_house_id = $request['ware_house_id'];
                $delivery->vehicale_id  = $request['vehicale_id'];
                $delivery->delivery_details  = $request['delivery_details'];
                $delivery->total_qty  = array_sum(array_column($items, 'order'));
                $delivery->total_receipt  = array_sum(array_column($items, 'receipt'));
                $delivery->total_pending  = array_sum(array_column($items, 'pending'));
                $delivery->delivery_date  = date('Y-m-d');
                $delivery->save();
                $delivery_id = $delivery->id;
                $delivery_invoice_no = $delivery->delivery_invoice_no;
                //delivery items
                foreach($items as $value){
                    $sale_item_id = $value->id;
                    $order = $value->order;
                    $receipt = $value->receipt;
                    $pending = $value->pending;
                    $delivery_item = new DeliveryItem;
                    $delivery_item->package_buy_id = $user['package_buy_id'];
                    $delivery_item->sale_id = $request['sale_id'];
                    $delivery_item->delivery_id = $delivery_id;
                    $delivery_item->delivery_invoice_no = $delivery_invoice_no;
                    $delivery_item->sale_item_id = $sale_item_id;
                    $delivery_item->order = $order;
                    $delivery_item->receipt = $receipt;
                    $delivery_item->pending = $pending;
                    $delivery_item->save();
                    //delivery history
                    $delivery_his = new DeliveryHistory;
                    $delivery_his->package_buy_id = $user['package_buy_id'];
                    $delivery_his->sale_id = $request['sale_id'];
                    $delivery_his->delivery_id = $delivery_id;
                    $delivery_his->delivery_item_id = $delivery_item->id;
                    $delivery_his->sale_item_id = $sale_item_id;
                    $delivery_his->order = $order;
                    $delivery_his->receipt = $receipt;
                    $delivery_his->pending = $pending;
                    $delivery_his->save();
                }
                //sale module update with delivery invoice no
                $sale = Sale::findOrFail($request['sale_id']);
                $sale->delivery_invoice_no = $delivery_invoice_no;
                $sale->save();
                if($delivery && $delivery_item && $sale){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Delivery Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Delivery",
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
    //DeliveryLists
    public function DeliveryLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $delivery = Delivery::with('sale_invoice_no')->where(['package_buy_id'=>$user['package_buy_id']])->select('id','sale_id','delivery_invoice_no','total_qty','total_receipt','total_pending','delivery_date','status')->orderBy('id','DESC')->paginate(15);
                if($delivery){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$delivery,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Delivery List Not Found",
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
    //DeliveryDetails
    public function DeliveryDetails(Request $request){
        $delivery = Delivery::with('sale','warehouse','vehicale')->where('id',$request['delivery_id'])->select('id','sale_id','delivery_invoice_no','ware_house_id','vehicale_id','delivery_details','total_qty','total_receipt','total_pending','delivery_date','status')->first();
        $items = DeliveryItem::with('delivery_item')->where('delivery_id',$request['delivery_id'])->select('id','sale_item_id','order','receipt','pending')->get();
        if($delivery && $items){
            return response()->json([
                'status'=>true,
                'lists'=>$delivery,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Delivery Details Not Found",
            ],200);
        }
    }
    //DeliveryDelete
    public function DeliveryDelete(Request $request){
        $delivery = Delivery::where('id',$request['id'])->select('delivery_invoice_no')->first();
        $delivery_challan = DeliveryChallan::where('delivery_invoice_no',$delivery['delivery_invoice_no'])->delete();
        $delivery = Delivery::where('id',$request['id'])->delete();
        if($delivery){
            return response()->json([
                'status'=>true,
                'message'=>"Delivery Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Delivery",
            ],200);
        }
    }
    //UpdateDelivery
    public function UpdateDelivery(Request $request){
        $items = json_decode($request->items);
        $delivery = Delivery::where('delivery_invoice_no',$request['delivery_invoice_no'])->first();
        if(isset($request['ware_house_id'])){
            $delivery->ware_house_id = $request['ware_house_id'];
        }
        if(isset($request['vehicale_id'])){
            $delivery->vehicale_id  = $request['vehicale_id'];
        }
        if(isset($request['delivery_details'])){
            $delivery->delivery_details  = $request['delivery_details'];
        }
        $total_qty = array_sum(array_column($items, 'order'));
        if(isset($total_qty )){
            $delivery->total_qty  = array_sum(array_column($items, 'order'));
        }
        $total_reciept = array_sum(array_column($items, 'receipt'));
        if(isset($total_reciept)){
            $delivery->total_receipt  = array_sum(array_column($items, 'receipt'));
        }
        $total_pending = array_sum(array_column($items, 'pending'));
        if(isset($total_pending)){
            $delivery->total_pending  = array_sum(array_column($items, 'pending'));
        }
        $delivery->save();
        //delivery items
        foreach($items as $value){
            $delivery_item_id = $value->id;
            $sale_item = DeliveryItem::where('id',$delivery_item_id)->first();
            if(isset($value->order)){
                $sale_item->order = $value->order;
            }
            if(isset($value->receipt)){
                $sale_item->receipt = $value->receipt;
            }
            if(isset($value->pending)){
                $sale_item->pending = $value->pending;
            }
            $sale_item->save();
            //delivery history
            $delivery_his = DeliveryHistory::where('delivery_item_id',$value->id)->first();
            if(isset($value->order)){
                $delivery_his->order = $value->order;
            }
            if(isset($value->receipt)){
                $delivery_his->receipt = $value->receipt;
            }
            if(isset($value->pending)){
                $delivery_his->pending = $value->pending;
            }
            $delivery_his->save();
        }
        if($delivery && $sale_item){
            return response()->json([
                'status'=>true,
                'message'=>"Delivery Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Update Delivery",
            ],200);
        }
    }
    //DeliveryInovoice
    public function DeliveryInovoice(Request $request){
        $delivery = Delivery::with('sale','warehouse','vehicale')->where('delivery_invoice_no',$request['delivery_invoice_no'])->select('id','sale_id','delivery_invoice_no','ware_house_id','vehicale_id','delivery_details','total_qty','total_receipt','total_pending','delivery_date','status')->first();
        $items = DeliveryItem::with('delivery_item')->where('delivery_invoice_no',$request['delivery_invoice_no'])->select('id','sale_item_id','order','receipt','pending')->get();
        if($delivery && $items){
            return response()->json([
                'status'=>true,
                'lists'=>$delivery,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Delivery Invoice Not Found",
            ],200);
        }
    }
    //PendingDelivery
    public function PendingDelivery(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $delivery = Sale::where(['package_buy_id'=>$user['package_buy_id']])->whereNull('delivery_invoice_no')->select('id','sale_invoice_no','sale_date','grand_total','paid_amount','due_amount')->orderBy('id','DESC')->paginate(15);
                if($delivery){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$delivery,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Delivery List Not Found",
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
    //CreateDeliveryChallan
    public function CreateDeliveryChallan(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'delivery_invoice_no'=>'required',
                    'vehicale_id'=>'required',
                    'challan_details'=>'required',
                    'items'=>'required',
                    // 'document'=>'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
                //document
                if($request->document){
                    $challan = time().'.'.$request->document->extension();
                    $request->document->move(public_path('images/delivery_challan'), $challan);
                }
                $items = json_decode($request['items']);
                //sale_invoice_no
                $sale_invoice_no = Sale::where('delivery_invoice_no',$request['delivery_invoice_no'])->select('sale_invoice_no')->first();
                $delivery = Delivery::where('delivery_invoice_no',$request['delivery_invoice_no'])->first();

                $delivery_challan = new DeliveryChallan;
                $delivery_challan->package_buy_id = $user['package_buy_id'];
                $delivery_challan->sale_invoice_no = $sale_invoice_no['sale_invoice_no'];
                $delivery_challan->delivery_invoice_no = $request['delivery_invoice_no'];
                $delivery_challan->vehicale_id = $request['vehicale_id'];
                $delivery_challan->delivery_challan_invoice_no = date('y').date('m').date('d').date('i').date('s');
                $delivery_challan->challan_details = $request['challan_details'];
                $delivery_challan->total_qty = $delivery['total_qty'];
                $delivery_challan->total_receipt = $delivery['total_receipt'];
                $delivery_challan->total_pending = $delivery['total_pending'];
                $delivery_challan->challan_date = date('Y-m-d');
                if(isset($challan)){
                    $delivery_challan->document = $_SERVER['HTTP_HOST'].'/public/images/delivery_challan/'.$challan;
                }
                $delivery_challan->save();
                $delivery_challan_id = $delivery_challan->id;
                $delivery_challan_invoice = $delivery_challan->delivery_challan_invoice_no;
                foreach($items as $value){
                    $delivery_item_id = $value->id;
                    $itm = DeliveryItem::with('delivery_item')->where('id',$delivery_item_id)->first();
                    $delivery_item = new DeliveryChallanItem;
                    $delivery_item->package_buy_id = $user['package_buy_id'];
                    $delivery_item->sale_invoice_no = $sale_invoice_no['sale_invoice_no'];
                    $delivery_item->delivery_invoice_no = $request['delivery_invoice_no'];
                    $delivery_item->delivery_challan_invoice_no = $delivery_challan_invoice;
                    $delivery_item->delivery_challan_id  = $delivery_challan_id;
                    $delivery_item->delivery_item_id = $delivery_item_id;
                    $delivery_item->unit_id = $itm['delivery_item']['unit_id'];
                    $delivery_item->product_id = $itm['delivery_item']['product_id'];
                    $delivery_item->avg_qty = $itm['delivery_item']['avg_qty'];
                    $delivery_item->bag = $itm['delivery_item']['bag'];
                    $delivery_item->qty = $itm['delivery_item']['qty'];
                    $delivery_item->rate = $itm['delivery_item']['rate'];
                    $delivery_item->amount = $itm['delivery_item']['amount'];
                    $delivery_item->order = $itm['order'];
                    $delivery_item->receipt = $itm['receipt'];
                    $delivery_item->pending = $itm['pending'];
                    $delivery_item->save();
                    //stock
                    $stock = Stock::where('product_id',$itm['delivery_item']['product_id'])->where('warehouse_id',$delivery['ware_house_id'])->first();
                    if($stock){
                        $stock->stock_old = $stock['stock_now'];
                        $stock->stock_now = $stock['stock_now'] - $itm['receipt'];
                        $stock->save();
                    }
                    //stock history
                    $stock_history = new StockHistory;
                    $stock_history->package_buy_id = $user['package_buy_id'];
                    $stock_history->stock_id = $stock->id;
                    $stock_history->product_id = $itm['delivery_item']['product_id'];
                    $stock_history->stock = $itm['receipt'];
                    $stock_history->date = date('Y-m-d');
                    $stock_history->stock_type = 'out';
                    $stock_history->save();
                }
                //sale table update with delivery challan invoice
                $sale_id = Delivery::where('delivery_invoice_no',$request['delivery_invoice_no'])->select('sale_id')->first();
                $sale = Sale::where('id',$sale_id['sale_id'])->first();
                $sale->challan_invoice_no = $delivery_challan_invoice;
                $sale->save();
                if($delivery_challan && $delivery_item){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Delivery Challan Created Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Create Delivery Challan",
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
    //ListsDeliveryChallan
    public function  ListsDeliveryChallan(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $delivery_challan = DeliveryChallan::where(['package_buy_id'=>$user['package_buy_id']])->select('id','sale_invoice_no','delivery_invoice_no','delivery_challan_invoice_no','vehicale_id','challan_details','total_qty','total_receipt','total_pending','challan_date','status','document')->orderBy('id','DESC')->paginate(15);
                if($delivery_challan){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$delivery_challan,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Delivery Challan List Not Found",
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
    //DeliveryChallanDetails
    public function DeliveryChallanDetails(Request $request){
        $delivery_challan = DeliveryChallan::with('vehicale')->where('delivery_challan_invoice_no',$request['delivery_challan_invoice_no'])->select('id','sale_invoice_no','delivery_invoice_no','vehicale_id','delivery_challan_invoice_no','challan_details','total_qty','total_receipt','total_pending','challan_date','document','status')->first();
        $customer_id = Sale::where('challan_invoice_no',$request['delivery_challan_invoice_no'])->select('id','acc_cus_sup_id')->first();
        $customer = AccCustomerSupplier::where('id',$customer_id['acc_cus_sup_id'])->select('id','acc_name','email','phone','address','acc_opening_balance','acc_hold_balance','profile_image')->first();
        $items = DeliveryChallanItem::with('product','unit')->where('delivery_challan_invoice_no',$request['delivery_challan_invoice_no'])->select('id','unit_id','product_id','avg_qty','bag','qty','rate','amount','order','receipt','pending')->get();
        if($delivery_challan && $items){
            return response()->json([
                'status'=>true,
                'customer'=>$customer,
                'lists'=>$delivery_challan,
                'items'=>$items,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Delivery Challan Details Not Found",
            ],200);
        }
    }
    //DeliveryChallanUpdate
    public function DeliveryChallanUpdate(Request $request){
        $delivery_challan = DeliveryChallan::where('delivery_challan_invoice_no',$request['delivery_challan_invoice_no'])->first();
        //document
        if($request->document){
            $image_name = basename($delivery_challan['document']);
            File::delete(public_path("images/delivery_challan/".$image_name));
            $challan = time().'.'.$request->document->extension();
            $request->document->move(public_path('images/delivery_challan'), $challan);
        }
        if(isset($request['vehicale_id'])){
            $delivery_challan->vehicale_id = $request['vehicale_id'];
        }
        if(isset($request['challan_details'])){
            $delivery_challan->challan_details = $request['challan_details'];
        }
        if(isset($challan)){
            $delivery_challan->document = $_SERVER['HTTP_HOST'].'/public/images/delivery_challan/'.$challan;
        }
        $delivery_challan->save();
        if($delivery_challan){
            return response()->json([
                'status'=>true,
                'message'=>"Delivery Challan Updated Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Update Delivery Challan",
            ],200);
        }
    }
    //DeliveryChallanDelete
    public function DeliveryChallanDelete(Request $request){
        $delivery_challan = DeliveryChallan::where('id',$request['id'])->delete();
        if($delivery_challan){
            return response()->json([
                'status'=>true,
                'message'=>"Delivery Challan Deleted Successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Something Is Wrong To Delete Delivery Challan",
            ],200);
        }
    }
    //DeliveryAdd
    public function DeliveryAdd(Request $request){
        $items = json_decode($request->items);
        $add = array_sum(array_column($items, 'add'));
        $delivery = Delivery::where('delivery_invoice_no',$request['delivery_invoice_no'])->first();
        $total_receipt = $delivery['total_receipt'] + $add;
        $total_pending = $delivery['total_pending'] - $add;
        if($delivery['status'] == "accept"){
            return response()->json([
                'status'=>false,
                'message'=>"Delivery Already Complete",
            ],200);
        }elseif($delivery['total_qty'] < $total_receipt){
            return response()->json([
                'status'=>false,
                'message'=>"Sorry! You Are Adding More Qty",
            ],200);
        }elseif($delivery['total_qty'] == $total_receipt){
            $delivery->total_receipt = $total_receipt;
            $delivery->total_pending = $total_pending;
            $delivery->status = 'accept';
            $delivery->save();
        }else{
            $delivery->total_receipt = $total_receipt;
            $delivery->total_pending = $total_pending;
            $delivery->save();
        }
        //item updating
        foreach($items as $value){
            $items = DeliveryItem::where('id',$value->id)->first();
            $items_receipt = $items['receipt'] + $value->add;
            $items_pending = $items['pending'] - $value->add;
            $items->receipt = $items_receipt;
            $items->pending = $items_pending;
            $items->save();
            //history
            $history = new DeliveryHistory;
            $history->package_buy_id = $delivery['package_buy_id'];
            $history->sale_id = $delivery['sale_id'];
            $history->delivery_id = $delivery['id'];
            $history->delivery_item_id = $items->id;
            $history->sale_item_id = $items->sale_item_id;
            $history->order = $items->order;
            $history->receipt = $value->add;
            $history->pending = $items->order - $value->add;
            $history->save();
        }
        return response()->json([
            'status'=>true,
            'message'=>"Delivery Added Successfully",
        ],200);
    }
    //StockLists
    public function StockLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $warehouse = WareHouse::where(['package_buy_id'=>$user['package_buy_id']])->select('id')->get();
                $stock = Stock::with('product','warehouse')->where(['package_buy_id'=>$user['package_buy_id']])->whereIn('id',$warehouse)->select('id','product_id','warehouse_id','stock_now','stock_old','sale_price','purchase_price','production_price','production_qty','date')->orderBy('id','DESC')->paginate(15);
                if($stock){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$stock,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Stock List Not Found",
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
    //StockDetails
    public function StockDetails(Request $request){
       $stock = Stock::with('product','warehouse','stock_history')->where('id',$request['id'])->select('id','product_id','warehouse_id','stock_now','stock_old','sale_price','purchase_price','production_price','production_qty','date')->first();
       if($stock){
            return response()->json([
                'status'=>true,
                'lists'=>$stock,
            ],200);
       }else{
            return response()->json([
                'status'=>false,
                'message'=>"Stock Details Not Found",
            ],200);
       }
    }
    //AlertStockLists
    public function AlertStockLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $stock = Stock::with('product','warehouse')->where('stock_now','<',5)->where(['package_buy_id'=>$user['package_buy_id']])->select('id','product_id','warehouse_id','stock_now','stock_old','sale_price','purchase_price','production_price','production_qty','date')->orderBy('id','DESC')->paginate(15);
                if($stock){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$stock,
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Alert Stock List Not Found",
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
    //CreateStockTransfer
    public function CreateStockTransfer(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $validator = Validator::make($request->all(), [
                    'from_warehouse_id'=>'required',
                    'to_warehouse_id'=>'required',
                    'items'=>'required'
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors'=>$validator->errors()], 400);
                }
               $items = json_decode($request['items']);
               $total_item = sizeof($items);
               //stock transfer
               $stock_tran = new StockTransfer;
               $stock_tran->package_buy_id = $user['package_buy_id'];
               $stock_tran->from_warehouse_id = $request['from_warehouse_id'];
               $stock_tran->to_warehouse_id = $request['to_warehouse_id'];
               $stock_tran->total_item = $total_item;
               $stock_tran->date = date('Y-m-d');
               $stock_tran->save();
                foreach($items as $value){
                    $stock = Stock::where('product_id',$value->product_id)->where('warehouse_id',$request['from_warehouse_id'])->first();
                    if($stock['stock_now'] > 0){
                        //from stock table update
                        $stock->stock_old = $stock['stock_now'];
                        $stock->stock_now = $stock['stock_now'] - $value->qty;
                        $stock->save();
                        //to stock table update
                        $to_stock = Stock::where('product_id',$value->product_id)->where('warehouse_id',$request['to_warehouse_id'])->first();
                        $to_stock->stock_old = $to_stock['stock_now'];
                        $to_stock->stock_now = $to_stock['stock_now'] + $value->qty;
                        $to_stock->save();
                        //stock history
                        $stock_history = new StockHistory;
                        $stock_history->package_buy_id = $user['package_buy_id'];
                        $stock_history->stock_id = $stock['id'];
                        $stock_history->product_id = $stock['product_id'];
                        $stock_history->stock = $value->qty;
                        $stock_history->stock_type = 'transfer';
                        $stock_history->date = date('Y-m-d');
                        $stock_history->save();
                        //stock transfer item
                        $stock_trn_his = new StockTransferItem;
                        $stock_trn_his->package_buy_id = $user['package_buy_id'];
                        $stock_trn_his->stock_transfer_id = $stock_tran->id;
                        $stock_trn_his->product_id = $value->product_id;
                        $stock_trn_his->qty = $value->qty;
                        $stock_trn_his->save();
                    }else{
                        return response()->json([
                            'status'=>false,
                            'message'=>$value->product_id."Not Available On Stock",
                        ],200);
                    }
                }
                if($stock_tran && $stock_trn_his){
                    return response()->json([
                        'status'=>true,
                        'message'=>"Stock Transfer Successfully",
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Something Is Wrong To Stock Transfer",
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
    //StockTransferLists
    public function StockTransferLists(Request $request){
        $user = User::with('usepackage')->where('rememberToken',$request['rememberToken'])->first();
        if($user){
            if($user['usepackage']['status'] == 'active'){
                $stock_transfer = StockTransfer::with('from_warehouse','to_warehouse')->where('package_buy_id',$user['package_buy_id'])->select('id','from_warehouse_id','to_warehouse_id','total_item','date')->get();
                if($stock_transfer){
                    return response()->json([
                        'status'=>true,
                        'lists'=>$stock_transfer
                    ],200);
                }else{
                    return response()->json([
                        'status'=>false,
                        'message'=>"Stock Transfer Lists Not Found",
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
    //StockTransferDetails
    public function StockTransferDetails(Request $request){
        $stock_transfer = StockTransfer::with('from_warehouse','to_warehouse','transfer_item')->where('id',$request['id'])->select('id','from_warehouse_id','to_warehouse_id','total_item','date')->get();
        if($stock_transfer){
            return response()->json([
                'status'=>true,
                'lists'=>$stock_transfer,
            ],200);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>"Stock Transfer Details Not Found",
            ],200);
        }
    }
}
