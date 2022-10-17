<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'package_buy_id','city_id', 'name', 'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'city_id' => 'integer',
        'name' => 'string',
        'status' => 'enum',
    ];

    //city relation
    public function city(){
        return $this->belongsTo(City::class, 'city_id')->select('id','name');
    }
}
