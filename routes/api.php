<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//login
Route::post('/login',[ApiController::class,'UserLogin']);
//packages
Route::get('/packages',[ApiController::class,'Packages']);
Route::post('/package-buy',[ApiController::class,'PackageBuy']);
//brand
Route::post('/create-brand',[ApiController::class,"CreateBrand"]);
Route::post('/brand-lists',[ApiController::class,"BrandLists"]);
Route::post('/update-brand',[ApiController::class,"UpdateBrand"]);
Route::post('/delete-brand',[ApiController::class,"DeleteBrand"]);
//category
Route::post('/create-category',[ApiController::class,"CreateCategory"]);
Route::post('/category-lists',[ApiController::class,"CategoryLists"]);
Route::post('/update-category',[ApiController::class,"UpdateCategory"]);
Route::post('/delete-category',[ApiController::class,"DeleteCategory"]);
//unit
Route::post('/create-unit',[ApiController::class,"CreateUnit"]);
Route::post('/unit-lists',[ApiController::class,"UnitLists"]);
Route::post('/update-unit',[ApiController::class,"UpdateUnit"]);
Route::post('/delete-unit',[ApiController::class,"DeleteUnit"]);
//lot gallary
Route::post('/create-lot-gallary',[ApiController::class,"CreateLotGallary"]);
Route::post('/lot-gallary-lists',[ApiController::class,"LotGallaryLists"]);
Route::post('/update-lot-gallary',[ApiController::class,"UpdateLotGallary"]);
Route::post('/delete-lot-gallary',[ApiController::class,"DeleteLotGallary"]);
//customer & supplier account
Route::post('create-customer-supplier-account',[ApiController::class,"CreateCusSupAcc"]);
Route::post('customer-supplier-account-lists',[ApiController::class,"CusSupAccLists"]);
Route::post('update-customer-supplier-account',[ApiController::class,"UpdateCusSupAcc"]);
//product
Route::post('create-product',[ApiController::class,"CreateProduct"]);
Route::post('product-lists',[ApiController::class,"ProductLists"]);
Route::post('update-product',[ApiController::class,"UpdateProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
Route::post('enable-product',[ApiController::class,"EnableProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
