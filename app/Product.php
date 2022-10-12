<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'package_buy_id', 'brand_id', 'categroy_id','unit_id', 'lot_gallary_id', 'acc_cus_sup_id','product_name', 'product_model', 'product_image','batch_no', 'serial_no','supplier_price','our_price','status',
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'brand_id' => 'integer',
        'categroy_id' => 'integer',
        'unit_id' => 'integer',
        'lot_gallary_id' => 'integer',
        'acc_cus_sup_id' => 'integer',
        'product_name' => 'string',
        'product_model' => 'string',
        'product_image' => 'string',
        'batch_no' => 'string',
        'serial_no' => 'string',
        'supplier_price' => 'biginteger',
        'our_price' => 'biginteger',
        'status' => 'enum',
    ];
}
