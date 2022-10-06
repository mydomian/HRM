<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionType extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'production_type', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'production_type' => 'string',
        'status' => 'enum',
    ];
}
