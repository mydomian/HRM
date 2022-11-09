<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'delivery_invoice_no','delivery_challan_invoice_no','delivery_challan_id','delivery_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'delivery_invoice_no' => 'string',
        'delivery_challan_invoice_no' => 'string',
        'delivery_challan_id' => 'integer',
        'delivery_item_id' => 'integer',
    ];

    // public function sale_challan_item(){
    //     return $this->belongsTo(SaleItem::class,'sale_item_id')->with('product','unit')->select('id','unit_id','product_id','sale_invoice_no','avg_qty','bag','qty','rate','amount');
    // }

}

