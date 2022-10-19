<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'package_buy_id','vehicle_id', 'city_id', 'thana_id','union_id','driver_name','driver_phone','father_name','driver_post_office','driver_village'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'vehicle_id' => 'integer',
        'city_id' => 'integer',
        'thana_id' => 'integer',
        'union_id' => 'integer',
        'driver_name' => 'string',
        'driver_phone' => 'biginteger',
        'father_name' => 'string',
        'driver_post_office' => 'text',
        'driver_village' => 'text',
    ];

    //city relation
    public function city(){
        return $this->belongsTo(City::class, 'city_id')->select('id','name');
    }
    //disctirct
    public function district(){
        return $this->belongsTo(District::class, 'district_id')->select('id','city_id','name');
    }
    //thana
    public function thana(){
        return $this->belongsTo(Thana::class, 'thana_id')->select('id','city_id','district_id','name');
    }
   //union
    public function union(){
        return $this->belongsTo(Union::class, 'union_id')->select('id','city_id','district_id','thana_id','name');
    }
}

