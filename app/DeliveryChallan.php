<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model
{
    protected $fillable = [
        'package_buy_id','sale_invoice_no','delivery_invoice_no','total_qty','total_receipt','total_pending', 'delivery_challan_invoice_no','vehicale_id', 'challan_details','challan_date','document',
        'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_invoice_no' => 'string',
        'delivery_invoice_no' => 'string',
        'delivery_challan_invoice_no' => 'string',
        'vehicale_id' => 'integer',
        'challan_date' => 'date',
        'challan_details' => 'longtext',
        'total_qty'=>'biginteger',
        'total_receipt'=>'biginteger',
        'total_pending'=>'biginteger',
        'document'=>'string',
        'status' => 'enum',
    ];
    public function getChallanDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //vehicale
    public function vehicale(){
        return $this->belongsTo(Vehicale::class, 'vehicale_id')->with('driver')->select('id','vehicle_name','vehicle_type','vehicle_no','vehicle_reg_no','owner_name','father_name','owner_phone','owner_post_office','owner_village');
    }
}
