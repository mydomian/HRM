<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleQuotation extends Model
{
    protected $fillable = [
        'package_buy_id', 'acc_cus_sup_id', 'quotation_invoice_no','product_order_by_id', 'quotation_date', 'quotation_sale_details','total_qty',
        'total_sale_amount','total_tax_amount','service_charge','shipping_cost','grand_total','paid_amount','due_amount','due_amount',
        'document'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'acc_cus_sup_id' => 'integer',
        'quotation_invoice_no' => 'string',
        'product_order_by_id' => 'integer',
        'quotation_date' => 'date',
        'quotation_sale_details' => 'longtext',
        'total_qty' => 'biginteger',
        'total_sale_amount' => 'biginteger',
        'total_tax_amount' => 'biginteger',
        'service_charge' => 'biginteger',
        'shipping_cost' => 'biginteger',
        'grand_total' => 'biginteger',
        'paid_amount' => 'biginteger',
        'due_amount' => 'biginteger',
        'document' => 'longtext',
    ];
    //quotation date formating
    public function getQuotationDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //acc customer and supplier
    public function acc_cus_sup(){
        return $this->belongsTo(AccCustomerSupplier::class,'acc_cus_sup_id','id')->select('id','acc_name','email','phone','address','acc_opening_balance','acc_hold_balance','profile_image');
    }

}


