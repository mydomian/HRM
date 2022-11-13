<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id','purchase_invoice_no','unit_id','product_id','avg_qty','qty','rate','amount','bag','receipt_invoice_no','order','receipt','pending','receipt_challan_id','receipt_challan_invoice_no','receipt_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'unit_id' => 'integer',
        'product_id' => 'integer',
        'avg_qty' => 'integer',
        'qty' => 'integer',
        'rate' => 'integer',
        'amount' => 'integer',
        'bag' => 'string',
        'purchase_invoice_no' => 'string',
        'receipt_invoice_no' => 'string',
        'receipt_challan_id' => 'biginteger',
        'receipt_challan_invoice_no' => 'string',
        'order'=>'biginteger',
        'receipt'=>'biginteger',
        'pending'=>'biginteger',
        'receipt_item_id' => 'biginteger'
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
        return $this->belongsTo(PurchaseItem::class,'purchase_invoice_no','purchase_invoice_no');
    }

}


