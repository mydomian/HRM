<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'package_name', 'package_price', 'package_feature','duration_days'
    ];
    protected $casts = [
        'package_name' => 'string',
        'package_price' => 'integer',
        'package_feature' => 'text',
        'duration_days' => 'bigInteger',
    ];

   

}
