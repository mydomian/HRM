<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'city_name', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'city_name' => 'string',
        'status' => 'enum',
    ];
}
