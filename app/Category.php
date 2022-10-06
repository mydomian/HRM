<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'name', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'name' => 'string',
        'status' => 'enum',
    ];
}
