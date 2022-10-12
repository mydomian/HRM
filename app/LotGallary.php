<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotGallary extends Model
{
    protected $fillable = [
        'package_buy_id', 'name', 'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'name' => 'string',
        'status' => 'enum',
    ];
}
