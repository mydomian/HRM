<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccArea extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'acc_area', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'acc_area' => 'string',
        'status' => 'enum',
    ];
}
