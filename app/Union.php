<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $fillable = [
        'package_buy_id','thana_id', 'name', 'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'thana_id' => 'integer',
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
    //thana
    public function thana(){
        return $this->belongsTo(Thana::class, 'thana_id')->select('id','city_id','district_id','name');
    }
}
