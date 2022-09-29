<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsagePackage extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'company_name','payment_type','account_no','transaction_id','amount','duration','start_date','end_date','database_name','status','date'
    ];
    protected $casts = [
        'user_id' => 'bigInteger',
        'package_id' => 'bigInteger',
        'company_name' => 'string',
        'payment_type' => 'string',
        'account_no' => 'text',
        'amount' => 'float',
        'transaction_id' => 'text',
        'duration' => 'bigInteger',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}

