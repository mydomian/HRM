<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PackageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('/admin')->namespace('Admin')->group(function () {
    Route::match(['get','post'],'/login',[AdminController::class,'Login']);
    Route::group(['middleware'=>['admin']],function(){
        Route::get('dashborad',[AdminController::class,'Index']);
        //package
        Route::get('packages',[PackageController::class,'index']);
        Route::post('package-create',[PackageController::class,'store']);
        Route::get('pacakge-edit/{package_id}',[PackageController::class,'edit']);
        Route::post('package-update',[PackageController::class,'update']);
        //users package request
        Route::get('package-request',[PackageController::class,'PackageRequest']);
        Route::get('package-view/{package_buy_id}',[PackageController::class,'PackageView']);
        Route::get('package-activate/{package_id}',[PackageController::class,'PackageActivate']);
        Route::get('package-deactivate/{package_id}',[PackageController::class,'PackageDeactivate']);
        Route::get('package-activated-lists',[PackageController::class,'PackageActivatedList']);
        Route::get('package-deactivated-lists',[PackageController::class,'PackagedeActivatedList']);
        //role-permission
        Route::get('role-permission',[AdminController::class,'RolePermission']);
        Route::post('role-create',[AdminController::class,'RoleCreate']);
        Route::get('role-edit/{role_id}',[AdminController::class,'RoleEdit']);
        //logout
        Route::get('logout',[AdminController::class,'Logout']);
    });
});

