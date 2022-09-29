<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Package;
use App\UsagePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{

    //packages
    public function Packages(){
        return response()->json(['packages'=>Package::latest()->get()]);
    }

    //database create
    public function GetStart(Request $request){
        //Usage Package Information
        $usage_package = new UsagePackage;
        $usage_package->user_id = $request['user_id'];
        $usage_package->package_id = $request['package_id'];
        $usage_package->company_name = $request['company_name'];
        $usage_package->payment_type = $request['payment_type'];
        $usage_package->account_no = $request['account_no'];
        $usage_package->transaction_id = $request['transaction_id'];
        $usage_package->amount = $request['amount'];
        $usage_package->duration = $request['duration'];
        $usage_package->start_date = null;
        $usage_package->end_date = null;
        $usage_package->database_name = null;
        $usage_package->status = 'deactive';
        $usage_package->date = date('Y-m-d');
        $usage_package->save();





    }
}
