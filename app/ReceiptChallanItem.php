<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReceiptChallanItem extends Model
{
    protected $fillable = [
        'package_buy_id', 'receipt_id', 'receipt_challan_id','receipt_item_id'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'receipt_id' => 'biginteger',
        'receipt_challan_id' => 'biginteger',
        'receipt_item_id' => 'biginteger'
    ];
}

