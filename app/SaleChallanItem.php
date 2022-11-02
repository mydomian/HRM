<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'sale_id', 'sale_challan_id','sale_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_id' => 'integer',
        'sale_challan_id' => 'integer',
        'sale_item_id' => 'integer',
    ];
    public function sale_challan_item(){
        return $this->belongsTo(SaleItem::class,'sale_item_id')->with('product','unit')->select('id','unit_id','product_id','sale_invoice_no','avg_qty','bag','qty','rate','amount');
    }
}
