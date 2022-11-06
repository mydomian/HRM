<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'package_buy_id', 'purchase_id', 'receipt_invoice_no','ware_house_id', 'vehicale_id', 'receipt_details','total_qty',
        'total_receipt','total_pending','receipt_date','status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'purchase_id' => 'integer',
        'receipt_invoice_no' => 'string',
        'ware_house_id' => 'integer',
        'vehicale_id' => 'biginteger',
        'receipt_details' => 'longtext',
        'total_qty' => 'biginteger',
        'total_receipt' => 'biginteger',
        'total_pending' => 'biginteger',
        'receipt_date' => 'date',
        'status' => 'enum',
    ];
    public function getReceiptDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id')->with(['acc_cus_sup','product_order_by'])->select('id','product_order_by_id','acc_cus_sup_id','purchase_invoice_no','purchase_date');
    }
    public function warehouse(){
        return $this->belongsTo(WareHouse::class,'ware_house_id')->select('id','name');
    }
    public function vehicale(){
        return $this->belongsTo(Vehicale::class,'vehicale_id')->select('id','vehicle_name','vehicle_type','vehicle_no','vehicle_reg_no','owner_name','father_name','owner_phone','owner_post_office','owner_village');
    }
}

