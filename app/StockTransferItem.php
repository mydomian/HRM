<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    protected $fillable = [
        'package_buy_id','stock_transfer_id','product_id', 'qty'
    ];
    protected $casts = [
        'package_buy_id' => 'biginteger',
        'stock_transfer_id' => 'biginteger',
        'product_id' => 'biginteger',
        'qty' => 'biginteger'
    ];
    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id')->select('id','product_name','product_model');
    }
}

