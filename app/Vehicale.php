<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicale extends Model
{
    protected $fillable = [
        'package_buy_id','city_id', 'district_id','thana_id','union_id','vehicle_name','vehicle_type','vehicle_no','vehicle_reg_no','owner_name','father_name','owner_phone','owner_post_office','owner_village'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'thana_id' => 'integer',
        'union_id' => 'integer',
        'vehicle_name' => 'string',
        'vehicle_type' => 'string',
        'vehicle_no' => 'string',
        'vehicle_reg_no' => 'string',
        'owner_name' => 'string',
        'father_name' => 'string',
        'owner_phone' => 'numeric',
        'owner_post_office' => 'string',
        'owner_village' => 'string',
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
    //driver
    public function driver(){
        return $this->hasMany(Driver::class,'vehicle_id')->with('city','district','thana','union')->select('id','city_id','district_id','thana_id','union_id','vehicle_id','driver_name','driver_phone','father_name','driver_post_office','driver_village');
    }
}

