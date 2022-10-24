<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleQuotation extends Model
{
    protected $fillable = [
        'package_buy_id', 'acc_cus_sup_id', 'quotation_invoice_no','product_order_by_id', 'quotation_date', 'quotation_sale_details',
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
        'total_sale_amount' => 'biginteger',
        'total_tax_amount' => 'biginteger',
        'service_charge' => 'biginteger',
        'shipping_cost' => 'biginteger',
        'grand_total' => 'biginteger',
        'paid_amount' => 'biginteger',
        'due_amount' => 'biginteger',
        'document' => 'longtext',
    ];
    // public function sale_quotation_items(){
    //     return $this->hasMany(SaleQuotationItem::class)->select('id','sale_quotation_id','product_id','avg_qty','bag','qty','rate','amount');
    // }
}


