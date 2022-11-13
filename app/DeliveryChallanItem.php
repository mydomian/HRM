<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'delivery_invoice_no','sale_invoice_no','unit_id','product_id','avg_qty','qty','rate','amount','bag','order','receipt','pending','delivery_challan_invoice_no','delivery_challan_id','delivery_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_invoice_no' => 'integer',
        'delivery_invoice_no' => 'string',
        'delivery_challan_invoice_no' => 'string',
        'delivery_challan_id' => 'integer',
        'delivery_item_id' => 'integer',
        'order'=>'biginteger',
        'receipt'=>'biginteger',
        'pending'=>'biginteger',
        'unit_id' => 'integer',
        'product_id' => 'integer',
        'avg_qty' => 'integer',
        'qty' => 'integer',
        'rate' => 'integer',
        'amount' => 'integer',
        'bag' => 'string',
    ];
    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id')->select('id','product_name');
    }
    //unit
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id')->select('id','name');
    }
    public function sale_challan_item(){
        return $this->belongsTo(SaleItem::class,'sale_item_id')->with('product','unit')->select('id','unit_id','product_id','sale_invoice_no','avg_qty','bag','qty','rate','amount');
    }

}

