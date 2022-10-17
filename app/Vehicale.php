<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicale extends Model
{
    protected $fillable = [
        'package_buy_id','vehicle_name', 'vehicle_type','vehicle_no','vehicle_reg_no','owner_name','owner_phone','owner_city_id','owner_district_id','owner_thana_id','owner_post_office','owner_village','status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'vehicle_name' => 'string',
        'vehicle_type' => 'string',
        'vehicle_no' => 'string',
        'vehicle_reg_no' => 'vehicle_reg_no',
        'owner_name' => 'string',
        'owner_phone' => 'biginteger',
        'owner_city_id' => 'biginteger',
        'owner_district_id' => 'biginteger',
        'owner_thana_id' => 'biginteger',
        'owner_post_office' => 'string',
        'status' => 'enum',
    ];
}

