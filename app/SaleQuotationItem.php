<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleQuotationItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'sale_quotation_id', 'unit_id','quotation_invoice_no', 'product_id', 'avg_qty','bag','qty','rate','amount',
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_quotation_id' => 'integer',
        'quotation_invoice_no' => 'string',
        'unit_id' => 'integer',
        'product_id' => 'integer',
        'avg_qty' => 'integer',
        'bag' => 'string',
        'qty' => 'biginteger',
        'rate' => 'biginteger',
        'amount' => 'biginteger',
    ];
    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id')->select('id','product_name');
    }
    //unit
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id')->select('id','name');
    }
}

