<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccCategory extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'acc_category', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'acc_category' => 'string',
        'status' => 'enum',
    ];
}
