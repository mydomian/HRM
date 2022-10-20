<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'package_buy_id', 'brand_id', 'category_id','unit_id', 'lot_gallary_id', 'acc_cus_sup_id','product_name', 'product_model', 'product_image','product_qty','batch_no', 'serial_no','supplier_price','our_price','status',
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'brand_id' => 'integer',
        'category_id' => 'integer',
        'unit_id' => 'integer',
        'lot_gallary_id' => 'integer',
        'acc_cus_sup_id' => 'integer',
        'product_name' => 'string',
        'product_model' => 'string',
        'product_image' => 'string',
        'product_qty' => 'biginteger',
        'batch_no' => 'string',
        'serial_no' => 'string',
        'supplier_price' => 'biginteger',
        'our_price' => 'biginteger',
        'status' => 'enum',
    ];

    //Eloquest Orm Relationship
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id')->select('id','name');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id')->select('id','name');
    }
    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id')->select('id','name');
    }
    public function lot_gallary(){
        return $this->belongsTo(LotGallary::class,'lot_gallary_id','id')->select('id','name');
    }
    public function customer_supplier(){
        return $this->belongsTo(AccCustomerSupplier::class,'acc_cus_sup_id','id')->select('id','acc_name');
    }
}
