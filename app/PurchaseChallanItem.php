<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'purchase_id', 'purchase_challan_id','purchase_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'purchase_id' => 'integer',
        'purchase_challan_id' => 'integer',
        'purchase_item_id' => 'integer',
    ];
    public function purchase_challan_item(){
        return $this->belongsTo(PurchaseItem::class,'purchase_item_id')->with('product','unit')->select('id','unit_id','product_id','purchase_invoice_no','avg_qty','bag','qty','rate','amount');
    }
}
