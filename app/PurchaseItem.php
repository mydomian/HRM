<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'purchase_id', 'unit_id','purchase_invoice_no', 'product_id', 'avg_qty','bag','qty','rate','amount',
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'purchase_id' => 'integer',
        'purchase_invoice_no' => 'string',
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
    public function receipt_challan_item(){
        return $this->hasMany(ReceiptChallanItem::class,'purchase_invoice_no','purchase_invoice_no');
    }
}
