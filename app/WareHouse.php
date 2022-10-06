<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'ware_house', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'ware_house' => 'string',
        'status' => 'enum',
    ];
}
