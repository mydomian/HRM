<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'purchase_owner_id','city_id', 'district_name', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'city_id' => 'integer',
        'district_name' => 'string',
        'status' => 'enum',
    ];
}
