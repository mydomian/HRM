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
            //nothing code is here
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
