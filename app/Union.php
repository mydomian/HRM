<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    protected $fillable = [
        'purchase_owner_id','thana_id', 'union_name', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'thana_id' => 'integer',
        'union_name' => 'string',
        'status' => 'enum',
    ];
}
