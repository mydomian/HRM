<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleChallan extends Model
{
    protected $fillable = [
        'package_buy_id', 'sale_id', 'sale_challan_invoice_no','vehicale_id', 'challan_details','challan_date','document',
        'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'sale_id' => 'integer',
        'sale_challan_invoice_no' => 'string',
        'vehicale_id' => 'integer',
        'challan_date' => 'date',
        'challan_details' => 'longtext',
        'document'=>'string',
        'status' => 'enum',
    ];
    public function getChallanDateAttribute($date){
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    }
    //vehicale
    public function vehicale(){
        return $this->belongsTo(Vehicale::class, 'vehicale_id')->select('id','vehicle_name');
    }
}
