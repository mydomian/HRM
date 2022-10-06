<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccCategory extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'bank_acc_category', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'bank_acc_category' => 'string',
        'status' => 'enum',
    ];
}
