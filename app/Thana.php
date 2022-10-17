<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $fillable = [
        'package_buy_id','city_id','district_id', 'name', 'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'name' => 'string',
        'status' => 'enum',
    ];

    //city relation
    public function city(){
        return $this->belongsTo(City::class, 'city_id')->select('id','name');
    }
    //disctirct
    public function district(){
        return $this->belongsTo(District::class, 'district_id')->select('id','city_id','name');
    }
}
