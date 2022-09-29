<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Package;
use App\UsagePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

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
        $package_requests = UsagePackage::where(['status'=>'deactive'])->get();
        return view('admin.package_request',compact('package_requests'));
    }
    //PackageActivate
    public function PackageActivate($package_id){
        //package activate
        $package_activate = UsagePackage::where('id',$package_id)->first();
        //database name created
        $company = $package_activate['company_name'];
        $company_name = preg_replace('/\s+/', '', $company);
        $package_activate->status = 'active';
        $package_activate->database_name = $company_name;
        $package_activate->save();

        //database create with table
        DB::statement("CREATE DATABASE IF NOT EXISTS $company_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

        //specify the server name and here it is localhost
        $server_name = "localhost";
        //specify the username - here it is root
        $user_name = "root";
        //specify the password - it is empty
        $password = "";
        //specify the database name - "My_company"
        $database_name = $company_name;
        // Creating the connection by specifying the connection details
        $connection = mysqli_connect($server_name, $user_name, $password,$database_name);
        //sql query tables for users
        $users = "CREATE TABLE  Users(
            id int(20) NULL AUTO_INCREMENT,
            name varchar(255) NULL,
            email varchar(255) NULL,
            phone varchar(255) NULL,
            password varchar(255) NULL,
            role_id int(10) NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP ,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )";
        if (mysqli_query($connection,$users)) {
            echo "Users table is successfully created database.";
        } else {
            echo "Error:" . mysqli_error($connection);
        }
        //sql query tables for sales
        $sales = "CREATE TABLE  Sales(
            id int(20) NULL AUTO_INCREMENT,
            customer_id int(20) NULL,
            user_id int(20) NULL,
            items text NULL,
            sales_details text NULL,
            total_sale_amount int(20) NULL,
            tax_amount int(20) NULL,
            service_charge int(20) NULL,
            shipping_cost int(20) NULL,
            grand_total int(20) NULL,
            paid_amount int(20) NULL,
            due_amount int(20) NULL,
            previous_balance int(20) NULL,
            current_balance int(20) NULL,
            date date NULL,
            payment_method varchar(255) NULL,
            document varchar(255) NULL,
            vehical_id int(20) NULL,
            invoice int(20) NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP ,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )";
        if (mysqli_query($connection,$sales)) {
            echo "Sales table is successfully created database.";
        } else {
            echo "Error:" . mysqli_error($connection);
        }
        //sql query tables for challans
        $sales = "CREATE TABLE  Challans(
            id int(20) NULL AUTO_INCREMENT,
            customer_id int(20) NULL,
            receiver_id int(20) NULL,
            user_id int(20) NULL,
            challan_no text NULL,
            challan_date date NULL,
            sale_inv_no varchar(255) NULL,
            note text NULL,
            vehicle_id int(20) NULL,
            driver_id int(20) NULL,
            items text NULL,
            paid_amount int(20) NULL,
            total int(50) NULL,
            rent_office_fee int(20) NULL,
            vehicle_rent int(20) NULL,
            advance_rent int(20) NULL,
            due_rent int(20) NULL,
            document varchar(255) NULL,
            status int(20) NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )";
        if (mysqli_query($connection,$sales)) {
            echo "Challan table is successfully created database.";
        } else {
            echo "Error:" . mysqli_error($connection);
        }
        //sql query tables for products
        $sales = "CREATE TABLE  Products(
            id int(20) NULL AUTO_INCREMENT,
            user_id int(20) NULL,
            category_id int(20) NULL,
            brand_id int(20) NULL,
            barcode_image text NULL,
            product_name varchar(255) NULL,
            product_image varchar(255) NULL,
            product_model text NULL,
            khat_account_id int(20) NULL,
            unit text NULL,
            lot_gallery text NULL,
            product_batch_no int(20) NULL,
            product_serial_no int(50) NULL,
            supplier_id int(20) NULL,
            supplier_price int(20) NULL,
            our_price int(20) NULL,
            date date NULL,
            status int(20) NULL,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        )";
        if (mysqli_query($connection,$sales)) {
            echo "Challan table is successfully created database.";
        } else {
            echo "Error:" . mysqli_error($connection);
        }
        //close the connection
        // mysqli_close($connection);

        return redirect('/admin/package-request')->with('message','User Package Activated Successfully');
    }

}
