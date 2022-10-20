<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankBranch extends Model
{
    protected $fillable = [
        'package_buy_id','bank_id', 'name', 'status'
    ];
    protected $casts = [
        'package_buy_id' => 'integer',
        'bank_id' => 'integer',
        'name' => 'string',
        'status' => 'enum',
    ];
}
