<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'purchase_id', 'receipt_id','receipt_invoice_no', 'purchase_item_id', 'order','receipt','pending'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'purchase_id' => 'biginteger',
        'receipt_id' => 'biginteger',
        'receipt_invoice_no' => 'string',
        'purchase_item_id' => 'biginteger',
        'order' => 'biginteger',
        'receipt' => 'biginteger',
        'pending' => 'biginteger'
    ];

    public function receipt_item(){
        return $this->belongsTo(PurchaseItem::class,'purchase_item_id')->with('product','unit')->select('id','unit_id','product_id','purchase_invoice_no','avg_qty','bag','qty','rate','amount');
    }

}
