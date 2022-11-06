<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptHistory extends Model
{
    protected $fillable = [
        'package_buy_id', 'purchase_id', 'receipt_id','receipt_item_id', 'purchase_item_id', 'order','receipt','pending'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'purchase_id' => 'biginteger',
        'receipt_id' => 'biginteger',
        'receipt_item_id' => 'biginteger',
        'purchase_item_id' => 'biginteger',
        'order' => 'biginteger',
        'receipt' => 'biginteger',
        'pending' => 'biginteger'
    ];

    public function receipt_item(){
        return $this->belongsTo(PurchaseItem::class,'purchase_item_id')->with('product','unit')->select('id','unit_id','product_id','purchase_invoice_no','avg_qty','bag','qty','rate','amount');
    }
}
