<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReceiptChallan extends Model
{
    protected $fillable = [
        'package_buy_id', 'receipt_invoice_no', 'receipt_challan_invoice_no','vehicale_id', 'challan_details', 'challan_date','document','status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'receipt_invoice_no' => 'string',
        'receipt_challan_invoice_no' => 'string',
        'vehicale_id' => 'biginteger',
        'challan_details' => 'longtext',
        'challan_date' => 'date',
        'document' => 'string',
        'status' => 'enum'
    ];
    public function getChallanDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //vehicale
    public function vehicale(){
        return $this->belongsTo(Vehicale::class, 'vehicale_id')->with('driver')->select('id','vehicle_name','vehicle_type','vehicle_no','vehicle_reg_no','owner_name','father_name','owner_phone','owner_post_office','owner_village');
    }
}

