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
Route::get('/single-brand/{brand_id}',[ApiController::class,"SingelBrand"]);
//category
Route::post('/create-category',[ApiController::class,"CreateCategory"]);
Route::post('/category-lists',[ApiController::class,"CategoryLists"]);
Route::post('/category',[ApiController::class,"Category"]);
Route::post('/update-category',[ApiController::class,"UpdateCategory"]);
Route::post('/delete-category',[ApiController::class,"DeleteCategory"]);
Route::get('/single-category/{category_id}',[ApiController::class,"SingelCategory"]);
//unit
Route::post('/create-unit',[ApiController::class,"CreateUnit"]);
Route::post('/unit-lists',[ApiController::class,"UnitLists"]);
Route::post('/unit',[ApiController::class,"Unit"]);
Route::post('/update-unit',[ApiController::class,"UpdateUnit"]);
Route::post('/delete-unit',[ApiController::class,"DeleteUnit"]);
Route::get('/single-unit/{unit_id}',[ApiController::class,"SingelUnit"]);
//lot gallary
Route::post('/create-lot-gallary',[ApiController::class,"CreateLotGallary"]);
Route::post('/lot-gallary-lists',[ApiController::class,"LotGallaryLists"]);
Route::post('/lot-gallary',[ApiController::class,"LotGallary"]);
Route::post('/update-lot-gallary',[ApiController::class,"UpdateLotGallary"]);
Route::post('/delete-lot-gallary',[ApiController::class,"DeleteLotGallary"]);
Route::get('/single-lot-gallary/{lot_gallary_id}',[ApiController::class,"SingleLotGallary"]);
//product order by
Route::post('/create-product-order-by',[ApiController::class,"CreateProductOrderBy"]);
Route::post('/product-order-by-lists',[ApiController::class,"ProductOrderByLists"]);
Route::post('/product-order-by',[ApiController::class,"ProductOrderBy"]);
Route::post('/update-product-order-by',[ApiController::class,"UpdateProductOrderBy"]);
Route::post('/delete-product-order-by',[ApiController::class,"DeleteProductOrderBy"]);
Route::get('/single-product-order-by/{product_order_by_id}',[ApiController::class,"SingleProductOrderBy"]);
//customer & supplier account
Route::post('create-customer-supplier-account',[ApiController::class,"CreateCusSupAcc"]);
Route::post('customer-supplier-account-lists',[ApiController::class,"CusSupAccLists"]);
Route::post('customer-supplier-account',[ApiController::class,"CusSupAcc"]);
Route::post('update-customer-supplier-account',[ApiController::class,"UpdateCusSupAcc"]);
Route::get('/single-cus-sup-acc/{cus_sup_acc_id}',[ApiController::class,"SingleCusSupAcc"]);
//product
Route::post('create-product',[ApiController::class,"CreateProduct"]);
Route::post('product-lists',[ApiController::class,"ProductLists"]);
Route::post('product',[ApiController::class,"Product"]);
Route::post('update-product',[ApiController::class,"UpdateProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
Route::post('enable-product',[ApiController::class,"EnableProduct"]);
Route::post('disable-product',[ApiController::class,"DisableProduct"]);
Route::post('delete-product',[ApiController::class,"DeleteProduct"]);
Route::get('/single-product/{product_id}',[ApiController::class,"SingleProduct"]);
//warehouse
Route::post('/create-warehouse',[ApiController::class,"CreateWarehouse"]);
Route::post('/warehouse-lists',[ApiController::class,"WarehouseLists"]);
Route::post('/warehouse',[ApiController::class,"Warehouse"]);
Route::post('/update-warehouse',[ApiController::class,"UpdateWarehouse"]);
Route::post('/delete-warehouse',[ApiController::class,"DeleteWarehouse"]);
Route::get('/single-warehouse/{warehouse_id}',[ApiController::class,"SingleWareHouse"]);
//thana
Route::post('/create-thana',[ApiController::class,"CreateThana"]);
Route::post('/thana-lists',[ApiController::class,"ThanaLists"]);
Route::post('/thana',[ApiController::class,"Thana"]);
Route::post('/update-thana',[ApiController::class,"UpdateThana"]);
Route::post('/delete-thana',[ApiController::class,"DeleteThana"]);
Route::get('/single-thana/{thana_id}',[ApiController::class,"SingleThana"]);
//city
Route::post('/create-city',[ApiController::class,"CreateCity"]);
Route::post('/city-lists',[ApiController::class,"CityLists"]);
Route::post('/city',[ApiController::class,"City"]);
Route::post('/update-city',[ApiController::class,"UpdateCity"]);
Route::post('/delete-city',[ApiController::class,"DeleteCity"]);
Route::get('/single-city/{city_id}',[ApiController::class,"SingleCity"]);
//district
Route::post('/create-district',[ApiController::class,"CreateDistrict"]);
Route::post('/district-lists',[ApiController::class,"DistrictLists"]);
Route::post('/district',[ApiController::class,"District"]);
Route::post('/update-district',[ApiController::class,"UpdateDistrict"]);
Route::post('/delete-district',[ApiController::class,"DeleteDistrict"]);
Route::get('/single-district/{district_id}',[ApiController::class,"SingleDistrict"]);
//union
Route::post('/create-union',[ApiController::class,"CreateUnion"]);
Route::post('/union-lists',[ApiController::class,"UnionLists"]);
Route::post('/union',[ApiController::class,"Union"]);
Route::post('/update-union',[ApiController::class,"UpdateUnion"]);
Route::post('/delete-union',[ApiController::class,"DeleteUnion"]);
Route::get('/single-union/{union_id}',[ApiController::class,"SingleUnion"]);
//inc-exp-acc-type
Route::post('/create-inc-exp-acc-type',[ApiController::class,"CreateIncExpAccType"]);
Route::post('/inc-exp-acc-type-lists',[ApiController::class,"IncExpAccTypeLists"]);
Route::post('/inc-exp-acc-type',[ApiController::class,"IncExpAccType"]);
Route::post('/update-inc-exp-acc-type',[ApiController::class,"UpdateIncExpAccType"]);
Route::post('/delete-inc-exp-acc-type',[ApiController::class,"DeleteIncExpAccType"]);
Route::get('/single-inc-exp-acc-type/{inc_exp_acc_type_id}',[ApiController::class,"SingleIncExpAccType"]);
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
Route::get('/single-production-type/{production_type_id}',[ApiController::class,"SingleProductionType"]);
//paymentmethod
Route::post('/create-payment-method',[ApiController::class,"CreatePaymentMethod"]);
Route::post('/payment-method-lists',[ApiController::class,"PaymentMethodLists"]);
Route::post('/payment-method',[ApiController::class,"PaymentMethod"]);
Route::post('/update-payment-method',[ApiController::class,"UpdatePaymentMethod"]);
Route::post('/delete-payment-method',[ApiController::class,"DeletePaymentMethod"]);
Route::get('/single-payment-method/{payment_method_id}',[ApiController::class,"SinglePaymentMethod"]);
//account area
Route::post('/create-acc-area',[ApiController::class,"CreateAccArea"]);
Route::post('/acc-area-lists',[ApiController::class,"AccAreaLists"]);
Route::post('/acc-area',[ApiController::class,"AccArea"]);
Route::post('/update-acc-area',[ApiController::class,"UpdateAccArea"]);
Route::post('/delete-acc-area',[ApiController::class,"DeleteAccArea"]);
Route::get('/single-acc-area/{acc_area_id}',[ApiController::class,"SingleAccArea"]);
//account category
Route::post('/create-acc-category',[ApiController::class,"CreateAccCategory"]);
Route::post('/acc-category-lists',[ApiController::class,"AccCategoryLists"]);
Route::post('/acc-category',[ApiController::class,"AccCategory"]);
Route::post('/update-acc-category',[ApiController::class,"UpdateAccCategory"]);
Route::post('/delete-acc-category',[ApiController::class,"DeleteAccCategory"]);
Route::get('/single-acc-category/{acc_category_id}',[ApiController::class,"SingleAccCategory"]);
//account type
Route::post('/create-acc-type',[ApiController::class,"CreateAccType"]);
Route::post('/acc-type-lists',[ApiController::class,"AccTypeLists"]);
Route::post('/acc-type',[ApiController::class,"AccType"]);
Route::post('/update-acc-type',[ApiController::class,"UpdateAccType"]);
Route::post('/delete-acc-type',[ApiController::class,"DeleteAccType"]);
Route::get('/single-acc-type/{acc_type_id}',[ApiController::class,"SingleAccType"]);
//bank account category
Route::post('/create-bank-acc-category',[ApiController::class,"CreateBankAccCategory"]);
Route::post('/bank-acc-category-lists',[ApiController::class,"BankAccCategoryLists"]);
Route::post('/bank-acc-category',[ApiController::class,"BankAccCategory"]);
Route::post('/update-bank-acc-category',[ApiController::class,"UpdateBankAccCategory"]);
Route::post('/delete-bank-acc-category',[ApiController::class,"DeleteBankAccCategory"]);
Route::get('/single-bank-acc-category/{bank_acc_category_id}',[ApiController::class,"SingleBankAccCategory"]);
//cash counter
Route::post('/create-cash-counter',[ApiController::class,"CreateCashCounter"]);
Route::post('/cash-counter-lists',[ApiController::class,"CashCounterLists"]);
Route::post('/cash-counter',[ApiController::class,"CashCounter"]);
Route::post('/update-cash-counter',[ApiController::class,"UpdateCashCounter"]);
Route::post('/delete-cash-counter',[ApiController::class,"DeleteCashCounter"]);
Route::get('/single-cash-counter/{cash_counter_id}',[ApiController::class,"SingleCashCounter"]);
//vehicale
Route::post('/create-vehicale',[ApiController::class,"CreateVehicale"]);
Route::post('/vehicale-lists',[ApiController::class,"VehicaleLists"]);
Route::post('/vehicale',[ApiController::class,"Vehicale"]);
Route::post('/update-vehicale',[ApiController::class,"UpdateVehicale"]);
Route::post('/delete-vehicale',[ApiController::class,"DeleteVehicale"]);
Route::get('/single-vehicale/{vehicale_id}',[ApiController::class,"SingleVehicale"]);
//vehicaletype
Route::post('/create-vehicale-type',[ApiController::class,"CreateVehicaleType"]);
Route::post('/vehicale-type-lists',[ApiController::class,"VehicaleTypeLists"]);
Route::post('/vehicale-type',[ApiController::class,"VehicaleType"]);
Route::post('/update-vehicale-type',[ApiController::class,"UpdateVehicaleType"]);
Route::post('/delete-vehicale-type',[ApiController::class,"DeleteVehicaleType"]);
Route::get('/single-vehicale-type/{vehicale_type_id}',[ApiController::class,"SingelVehicaleType"]);
//driver
Route::post('/create-driver',[ApiController::class,"CreateDriver"]);
Route::post('/driver-lists',[ApiController::class,"DriverLists"]);
Route::post('/driver',[ApiController::class,"Driver"]);
Route::post('/update-driver',[ApiController::class,"UpdateDriver"]);
Route::post('/delete-driver',[ApiController::class,"DeleteDriver"]);
Route::get('/single-driver/{driver_id}',[ApiController::class,"SingleDriver"]);
//bank account category
Route::post('/create-bank',[ApiController::class,"CreateBank"]);
Route::post('/bank-lists',[ApiController::class,"BankLists"]);
Route::post('/bank',[ApiController::class,"Bank"]);
Route::post('/update-bank',[ApiController::class,"UpdateBank"]);
Route::post('/delete-bank',[ApiController::class,"DeleteBank"]);
Route::get('/single-bank/{bank_id}',[ApiController::class,"SingleBank"]);
//bank branch
Route::post('/create-bank-branch',[ApiController::class,"CreateBankBranch"]);
Route::post('/bank-branch-lists',[ApiController::class,"BankBranchLists"]);
Route::post('/bank-branch',[ApiController::class,"BankBranch"]);
Route::post('/update-bank-branch',[ApiController::class,"UpdateBankBranch"]);
Route::post('/delete-bank-branch',[ApiController::class,"DeleteBankBranch"]);
Route::get('/single-bank-branch/{bank_branch_id}',[ApiController::class,"SingleBankBranch"]);
//bank designation
Route::post('/create-designation',[ApiController::class,"CreateDesignation"]);
Route::post('/designation-lists',[ApiController::class,"DesignationLists"]);
Route::post('/designation',[ApiController::class,"Designation"]);
Route::post('/update-designation',[ApiController::class,"UpdateDesignation"]);
Route::post('/delete-designation',[ApiController::class,"DeleteDesignation"]);
Route::get('/single-designation/{designation_id}',[ApiController::class,"SingleDesignation"]);
//bank account type
Route::post('/create-bank-acc-type',[ApiController::class,"CreateBankAccountType"]);
Route::post('/bank-acc-type-lists',[ApiController::class,"BankAccountTypeLists"]);
Route::post('/bank-acc-type',[ApiController::class,"BankAccountType"]);
Route::post('/update-bank-acc-type',[ApiController::class,"UpdateBankAccountType"]);
Route::post('/delete-bank-acc-type',[ApiController::class,"DeleteBankAccountType"]);
Route::get('/single-bank-acc-type/{bank_acc_type_id}',[ApiController::class,"SingleBankAccountType"]);
//sale quotation
Route::post('/create-sale-quotation',[ApiController::class,"SaleQuotation"]);
Route::post('/update-sale-quotation',[ApiController::class,"UpdateSaleQuotation"]);
Route::post('/sale-quotation-lists',[ApiController::class,"SaleQuotationLists"]);
Route::post('/sale-quotation-details',[ApiController::class,"SaleQuotationDetails"]);
Route::post('/delete-sale-quotation',[ApiController::class,"SaleQuotationDelete"]);
//purchase quotation
Route::post('/create-purchase-quotation',[ApiController::class,"PurchaseQuotation"]);
Route::post('/update-purchase-quotation',[ApiController::class,"UpdatePurchaseQuotation"]);
Route::post('/purchase-quotation-lists',[ApiController::class,"PurchaseQuotationLists"]);
Route::post('/purchase-quotation-details',[ApiController::class,"PurchaseQuotationDetails"]);
Route::post('/delete-purchase-quotation',[ApiController::class,"PurchaseQuotationDelete"]);
//purchase
Route::post('/create-purchase',[ApiController::class,"Purchase"]);
Route::post('/update-purchase',[ApiController::class,"UpdatePurchase"]);
Route::post('/purchase-lists',[ApiController::class,"PurchaseLists"]);
Route::post('/purchase-details',[ApiController::class,"PurchaseDetails"]);
Route::post('/delete-purchase',[ApiController::class,"PurchaseDelete"]);
//sale
Route::post('/create-sale',[ApiController::class,"Sale"]);
Route::post('/update-sale',[ApiController::class,"UpdateSale"]);
Route::post('/sale-lists',[ApiController::class,"SaleLists"]);
Route::post('/sale-details',[ApiController::class,"SaleDetails"]);
Route::post('/delete-sale',[ApiController::class,"SaleDelete"]);
//receipt
Route::post('/create-receipt',[ApiController::class,"CreateReceipt"]);
Route::post('/receipt-lists',[ApiController::class,"ReceiptLists"]);
Route::post('/receipt-details',[ApiController::class,"ReceiptDetails"]);
Route::post('/update-receipt',[ApiController::class,"ReceiptUpdate"]);
Route::post('/receipt-delete',[ApiController::class,"ReceiptDelete"]);
Route::post('/pending-receipt',[ApiController::class,"PendingReceipt"]);
Route::post('/receipt-invoice',[ApiController::class,"ReceiptInvoice"]);
Route::post('/add-receipt',[ApiController::class,"ReceiptAdd"]);
//receipt challan
Route::post('/create-receipt-challan',[ApiController::class,"CreateReceiptChallan"]);
Route::post('/receipt-challan-lists',[ApiController::class,"ReceiptChallanLists"]);
Route::post('/receipt-challan-details',[ApiController::class,"ReceiptChallanDetails"]);
Route::post('/receipt-challan-delete',[ApiController::class,"ReceiptChallanDelete"]);
Route::post('/update-receipt-challan',[ApiController::class,"ReceiptChallanUpdate"]);
Route::post('/receipt-challan-invoice',[ApiController::class,"ReceiptChallanInvoice"]);
//delivery
Route::post('/create-delivery',[ApiController::class,'CreateDelivery']);
Route::post('/delivery-lists',[ApiController::class,'DeliveryLists']);
Route::post('/delivery-details',[ApiController::class,'DeliveryDetails']);
Route::post('/delivery-delete',[ApiController::class,'DeliveryDelete']);
Route::post('/update-delivery',[ApiController::class,'UpdateDelivery']);
Route::post('/pending-delivery',[ApiController::class,'PendingDelivery']);
Route::post('/delivery-invoice',[ApiController::class,'DeliveryInovoice']);
Route::post('/add-delivery',[ApiController::class,'DeliveryAdd']);
//delivery challan
Route::post('/create-delivery-challan',[ApiController::class,'CreateDeliveryChallan']);
Route::post('/delivery-challan-lists',[ApiController::class,'ListsDeliveryChallan']);
Route::post('/delivery-challan-details',[ApiController::class,'DeliveryChallanDetails']);
Route::post('/update-delivery-challan',[ApiController::class,'DeliveryChallanUpdate']);
Route::post('/delivery-challan-delete',[ApiController::class,'DeliveryChallanDelete']);
//stock
Route::post('/stock-lists',[ApiController::class,'StockLists']);
Route::post('/alert-stock-lists',[ApiController::class,'AlertStockLists']);
Route::post('/stock-details',[ApiController::class,'StockDetails']);
//stock transfer
Route::post('/create-stock-transfer',[ApiController::class,'CreateStockTransfer']);
Route::post('/stock-transfer-lists',[ApiController::class,'StockTransferLists']);
Route::post('/stock-transfer-details',[ApiController::class,'StockTransferDetails']);
