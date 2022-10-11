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
