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
Route::post('/brand',[ApiController::class,"Brand"]);
Route::post('/update-brand',[ApiController::class,"UpdateBrand"]);
Route::post('/delete-brand',[ApiController::class,"DeleteBrand"]);
//category
Route::post('/create-category',[ApiController::class,"CreateCategory"]);
Route::post('/category-lists',[ApiController::class,"CategoryLists"]);
Route::post('/category',[ApiController::class,"Category"]);
Route::post('/update-category',[ApiController::class,"UpdateCategory"]);
Route::post('/delete-category',[ApiController::class,"DeleteCategory"]);
//unit
Route::post('/create-unit',[ApiController::class,"CreateUnit"]);
Route::post('/unit-lists',[ApiController::class,"UnitLists"]);
Route::post('/unit',[ApiController::class,"Unit"]);
Route::post('/update-unit',[ApiController::class,"UpdateUnit"]);
Route::post('/delete-unit',[ApiController::class,"DeleteUnit"]);
//lot gallary
Route::post('/create-lot-gallary',[ApiController::class,"CreateLotGallary"]);
Route::post('/lot-gallary-lists',[ApiController::class,"LotGallaryLists"]);
Route::post('/lot-gallary',[ApiController::class,"LotGallary"]);
Route::post('/update-lot-gallary',[ApiController::class,"UpdateLotGallary"]);
Route::post('/delete-lot-gallary',[ApiController::class,"DeleteLotGallary"]);
//customer & supplier account
Route::post('create-customer-supplier-account',[ApiController::class,"CreateCusSupAcc"]);
Route::post('customer-supplier-account-lists',[ApiController::class,"CusSupAccLists"]);
Route::post('customer-supplier-account',[ApiController::class,"CusSupAcc"]);
Route::post('update-customer-supplier-account',[ApiController::class,"UpdateCusSupAcc"]);
//product
Route::post('create-product',[ApiController::class,"CreateProduct"]);
Route::post('product-lists',[ApiController::class,"ProductLists"]);
Route::post('update-product',[ApiController::class,"UpdateProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
Route::post('enable-product',[ApiController::class,"EnableProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
//warehouse
Route::post('/create-warehouse',[ApiController::class,"CreateWarehouse"]);
Route::post('/warehouse-lists',[ApiController::class,"WarehouseLists"]);
Route::post('/warehouse',[ApiController::class,"Warehouse"]);
Route::post('/update-warehouse',[ApiController::class,"UpdateWarehouse"]);
Route::post('/delete-warehouse',[ApiController::class,"DeleteWarehouse"]);
//thana
Route::post('/create-thana',[ApiController::class,"CreateThana"]);
Route::post('/thana-lists',[ApiController::class,"ThanaLists"]);
Route::post('/thana',[ApiController::class,"Thana"]);
Route::post('/update-thana',[ApiController::class,"UpdateThana"]);
Route::post('/delete-thana',[ApiController::class,"DeleteThana"]);
//city
Route::post('/create-city',[ApiController::class,"CreateCity"]);
Route::post('/city-lists',[ApiController::class,"CityLists"]);
Route::post('/city',[ApiController::class,"City"]);
Route::post('/update-city',[ApiController::class,"UpdateCity"]);
Route::post('/delete-city',[ApiController::class,"DeleteCity"]);
//district
Route::post('/create-district',[ApiController::class,"CreateDistrict"]);
Route::post('/district-lists',[ApiController::class,"DistrictLists"]);
Route::post('/district',[ApiController::class,"District"]);
Route::post('/update-district',[ApiController::class,"UpdateDistrict"]);
Route::post('/delete-district',[ApiController::class,"DeleteDistrict"]);
//union
Route::post('/create-union',[ApiController::class,"CreateUnion"]);
Route::post('/union-lists',[ApiController::class,"UnionLists"]);
Route::post('/union',[ApiController::class,"Union"]);
Route::post('/update-union',[ApiController::class,"UpdateUnion"]);
Route::post('/delete-union',[ApiController::class,"DeleteUnion"]);
//inc-exp-acc-type
Route::post('/create-inc-exp-acc-type',[ApiController::class,"CreateIncExpAccType"]);
Route::post('/inc-exp-acc-type-lists',[ApiController::class,"IncExpAccTypeLists"]);
Route::post('/inc-exp-acc-type',[ApiController::class,"IncExpAccType"]);
Route::post('/update-inc-exp-acc-type',[ApiController::class,"UpdateIncExpAccType"]);
Route::post('/delete-inc-exp-acc-type',[ApiController::class,"DeleteIncExpAccType"]);
//inc-exp-pay-method
Route::post('/create-inc-exp-pay-method',[ApiController::class,"CreateIncExpPayMethod"]);
Route::post('/inc-exp-pay-method-lists',[ApiController::class,"IncExpPayMethodLists"]);
Route::post('/inc-exp-pay-method',[ApiController::class,"IncExpPayMethod"]);
Route::post('/update-inc-exp-pay-method',[ApiController::class,"UpdateIncExpPayMethod"]);
Route::post('/delete-inc-exp-pay-method',[ApiController::class,"DeleteIncExpPayMethod"]);
//production-type
Route::post('/create-production-type',[ApiController::class,"CreateProductionType"]);
Route::post('/production-type-lists',[ApiController::class,"ProductionTypeLists"]);
Route::post('/production-type',[ApiController::class,"ProductionType"]);
Route::post('/update-production-type',[ApiController::class,"UpdateProductionType"]);
Route::post('/delete-production-type',[ApiController::class,"DeleteProductionType"]);
//paymentmethod
Route::post('/create-payment-method',[ApiController::class,"CreatePaymentMethod"]);
Route::post('/payment-method-lists',[ApiController::class,"PaymentMethodLists"]);
Route::post('/payment-method',[ApiController::class,"PaymentMethod"]);
Route::post('/update-payment-method',[ApiController::class,"UpdatePaymentMethod"]);
Route::post('/delete-payment-method',[ApiController::class,"DeletePaymentMethod"]);
