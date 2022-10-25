<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'package_buy_id', 'acc_cus_sup_id', 'quotation_invoice_no','product_order_by_id', 'purchase_date', 'purchase_details',
        'total_purchase_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','payment_method_id','document'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'acc_cus_sup_id' => 'integer',
        'quotation_invoice_no' => 'string',
        'product_order_by_id' => 'integer',
        'purchase_date' => 'date',
        'purchase_details' => 'longtext',
        'total_purchase_amount' => 'biginteger',
        'total_tax_amount' => 'biginteger',
        'service_charge' => 'biginteger',
        'shipping_cost' => 'biginteger',
        'grand_total' => 'biginteger',
        'paid_amount' => 'biginteger',
        'due_amount' => 'biginteger',
        'document' => 'longtext',
        'payment_method_id' => 'biginteger',
    ];
    //quotation date formating
    public function getPurchaseDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //acc customer and supplier
    public function acc_cus_sup(){
        return $this->belongsTo(AccCustomerSupplier::class,'acc_cus_sup_id','id')->select('id','acc_name','email','phone','address','acc_opening_balance','acc_hold_balance','profile_image');
    }
    //payment_method
    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class,'payment_method_id','id')->select('id','name');
    }
}

