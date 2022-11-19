<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    protected $fillable = [
        'package_buy_id','from_warehouse_id','to_warehouse_id', 'total_item', 'date'
    ];
    protected $casts = [
        'package_buy_id' => 'biginteger',
        'from_warehouse_id' => 'biginteger',
        'to_warehouse_id' => 'biginteger',
        'total_item' => 'biginteger',
        'date' => 'date'
    ];
    public function getDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //from warehouse
    public function from_warehouse(){
        return $this->belongsTo(WareHouse::class,'from_warehouse_id')->select('id','name');
    }
    //to warehouse
    public function to_warehouse(){
        return $this->belongsTo(WareHouse::class,'to_warehouse_id')->select('id','name');
    }
    //stock transfer item
    public function transfer_item(){
        return $this->hasMany(StockTransferItem::class,'stock_transfer_id')->with('product')->select('id','stock_transfer_id','product_id','qty');
    }

}
