<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'payment_method', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'payment_method' => 'string',
        'status' => 'enum',
    ];
}
