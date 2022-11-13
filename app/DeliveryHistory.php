<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryHistory extends Model
{
    protected $fillable = [
        'package_buy_id', 'sale_id', 'delivery_id','delivery_item_id', 'sale_item_id', 'order','receipt','pending'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_id' => 'biginteger',
        'delivery_id' => 'biginteger',
        'delivery_item_id' => 'biginteger',
        'sale_item_id' => 'biginteger',
        'order' => 'biginteger',
        'receipt' => 'biginteger',
        'pending' => 'biginteger'
    ];

    public function sale_item(){
        return $this->belongsTo(SaleItem::class,'sale_item_id')->with('product','unit')->select('id','unit_id','product_id','sale_invoice_no','avg_qty','bag','qty','rate','amount');
    }
}
