<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'package_buy_id', 'sale_id', 'delivery_invoice_no','ware_house_id', 'vehicale_id', 'delivery_details','total_qty',
        'total_receipt','total_pending','delivery_date','status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_id' => 'integer',
        'delivery_invoice_no' => 'string',
        'ware_house_id' => 'integer',
        'vehicale_id' => 'biginteger',
        'delivery_details' => 'longtext',
        'total_qty' => 'biginteger',
        'total_receipt' => 'biginteger',
        'total_pending' => 'biginteger',
        'delivery_date' => 'date',
        'status' => 'enum',
    ];
    public function getDeliveryDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    public function sale(){
        return $this->belongsTo(Sale::class,'sale_id')->with('acc_cus_sup','product_order_by')->select('id','acc_cus_sup_id','product_order_by_id','sale_invoice_no','sale_date');
    }
    public function warehouse(){
        return $this->belongsTo(WareHouse::class,'ware_house_id')->select('id','name');
    }
    public function vehicale(){
        return $this->belongsTo(Vehicale::class,'vehicale_id')->with('driver')->select('id','vehicle_name','vehicle_type','vehicle_no','vehicle_reg_no','owner_name','father_name','owner_phone','owner_post_office','owner_village');
    }
    public function sale_invoice_no(){
        return $this->belongsTo(Sale::class,'sale_id')->select('id','sale_invoice_no');
    }
}
