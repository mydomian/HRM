<?php

namespace App;

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
}
