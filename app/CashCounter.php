<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashCounter extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'cash_counter', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'cash_counter' => 'string',
        'status' => 'enum',
    ];
}
