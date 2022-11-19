<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'package_buy_id','product_id','warehouse_id','stock_now', 'stock_old','sale_price','purchase_price','production_price','production_qty','date'
    ];
    protected $casts = [
        'package_buy_id' => 'biginteger',
        'product_id' => 'biginteger',
        'warehouse_id' => 'biginteger',
        'stock_now' => 'biginteger',
        'stock_old' => 'biginteger',
        'sale_price' => 'biginteger',
        'purchase_price' => 'biginteger',
        'production_price' => 'biginteger',
        'production_qty' => 'biginteger',
        'date' => 'date',
    ];

    public function getDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //product
    public function product(){
        return $this->belongsTo(Product::class,'product_id')->with('unit')->select('id','unit_id','product_name','product_model');
    }
    //warehouse
    public function warehouse(){
        return $this->belongsTo(WareHouse::class,'warehouse_id')->select('id','name');
    }
    //stock history
    public function stock_history(){
        return $this->hasMany(StockHistory::class,'stock_id')->select('id','stock_id','stock','stock_type','date');
    }
}
