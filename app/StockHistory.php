<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'package_buy_id','stock_id','product_id', 'stock', 'stock_type','date'
    ];
    protected $casts = [
        'package_buy_id' => 'biginteger',
        'stock_id' => 'biginteger',
        'product_id' => 'biginteger',
        'stock' => 'biginteger',
        'stock_type' => 'enum',
        'date' => 'date'
    ];
    public function getDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id')->with('unit')->select('id','unit_id','product_name','product_model');
    }
}

