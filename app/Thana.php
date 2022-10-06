<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $fillable = [
        'purchase_owner_id','district_id', 'thana_name', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'district_id' => 'integer',
        'thana_name' => 'string',
        'status' => 'enum',
    ];
}
