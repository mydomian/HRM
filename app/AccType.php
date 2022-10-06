<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccType extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'acc_type', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'acc_type' => 'string',
        'status' => 'enum',
    ];
}
