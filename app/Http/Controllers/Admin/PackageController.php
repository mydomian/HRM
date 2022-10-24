<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Package;
use App\PackageBuy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;
class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::latest()->get();
        return view('admin.packages',compact('packages'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = Package::create($request->all());
        return redirect('/admin/packages')->with('message','Package Created Successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return response()->json($package);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $package = Package::where('id',$request['package_id'])->first();
        $package->package_name = $request['package_name'];
        $package->package_price = $request['package_price'];
        $package->package_feature = $request['package_feature'];
        $package->duration_days = $request['duration_days'];
        $package->save();
        return redirect('/admin/packages')->with('message','Package Updated Successfully');
    }
    //PackageRequest
    public function PackageRequest(){
        $package_requests = PackageBuy::with('packages')->where(['status'=>'pending'])->get();
        return view('admin.package_request',compact('package_requests'));
    }
    //PackageActivate
    public function PackageActivate($package_buy_id){
        $pass = Str::random(15);
        //package activate
        $package_activate = PackageBuy::where('id',$package_buy_id)->first();
        $package_activate->status = 'active';
        $package_activate->password = $pass;
        $package_activate->save();
        //user package admin role create
        $exits = User::where('email',$package_activate['email'])->first();
        if($exits){
            $exits->password = Hash::make($pass);
            $exits->save();
        }else{
            $user = new User;
            $user->package_buy_id  = $package_buy_id;
            $user->name = $package_activate['name'];
            $user->email = $package_activate['email'];
            $user->role_as = "admin";
            $user->password = Hash::make($pass);
            $user->save();
        }

        // //database create with table
        // $database_name = $package_activate['database_name'];
        // $server_name = "localhost";
        // $user_name = "root";
        // $password = "";
        // DB::statement("CREATE DATABASE IF NOT EXISTS $database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        // $connection = mysqli_connect($server_name, $user_name, $password,$database_name);
        // //sql query tables for sale_quotations
        // $sale_quotations = "CREATE TABLE  sale_quotations(
        //     id int(20) NULL AUTO_INCREMENT,
        //     acc_cus_sup_id bigint(20) NULL,
        //     quotation_invoice_no varchar(255) NULL,
        //     product_order_by bigint(20) NULL,
        //     quotation_date date NULL,
        //     quotation_sale_details text(1000) NULL,
        //     total_sale_amount bigint(20) NULL,
        //     total_tax_amount bigint(20) NULL,
        //     service_charge bigint(20) NULL,
        //     shipping_cost bigint(20) NULL,
        //     grand_total bigint(20) NULL,
        //     paid_amount bigint(20) NULL,
        //     due_amount bigint(20) NULL,
        //     document text(500) NULL,
        //     created_at timestamp DEFAULT CURRENT_TIMESTAMP ,
        //     updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        //     PRIMARY KEY (id)
        // )";
        // if (mysqli_query($connection,$sale_quotations)) {
        //     //nothing code is here
        // } else {
        //     echo "Error:" . mysqli_error($connection);
        // }
        // //sql query tables for sale_quotations_items
        // $sale_quotations_items = "CREATE TABLE  sale_quotations_items(
        //     id int(20) NULL AUTO_INCREMENT,
        //     sale_quotation_id bigint(20) NULL,
        //     quotation_invoice_no varchar(255) NULL,
        //     product_id bigint(20) NULL,
        //     avg_qty bigint(20) NULL,
        //     bag varchar(255) NULL,
        //     qty bigint(20) NULL,
        //     unit_id bigint(20) NULL,
        //     rate varchar(255) NULL,
        //     amount bigint(20) NULL,
        //     created_at timestamp DEFAULT CURRENT_TIMESTAMP ,
        //     updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        //     PRIMARY KEY (id)
        // )";

        // if (mysqli_query($connection,$sale_quotations_items)) {
        //     //nothing code is here
        // } else {
        //     echo "Error:" . mysqli_error($connection);
        // }












        return redirect('/admin/package-request')->with('message','User Package Activated Successfully');
    }
    //PackageDeactivate
    public function PackageDeactivate($package_buy_id){
        //package activate
        $package_activate = PackageBuy::where('id',$package_buy_id)->first();
        $package_activate->status = 'deactive';
        $package_activate->save();
        return redirect('/admin/package-request')->with('warning','User Package Deactivated Successfully');
    }
    //PackageView
    public function PackageView($package_buy_id){
        $package_buy = PackageBuy::where('id',$package_buy_id)->first();
        return $package_buy;
    }
    //PackageActivatedList
    public function PackageActivatedList(){
        $package_activated_lists = PackageBuy::with('packages')->where(['status'=>'active'])->get();
        return view('admin.package_activate_lists',compact('package_activated_lists'));
    }
    //PackagedeActivatedList
    public function PackagedeActivatedList(){
        $package_deactivated_lists = PackageBuy::with('packages')->where(['status'=>'deactive'])->get();
        return view('admin.package_deactivate_lists',compact('package_deactivated_lists'));
    }

}
