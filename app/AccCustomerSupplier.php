<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccCustomerSupplier extends Model
{
    protected $fillable = [
        'package_buy_id','acc_name','acc_no', 'email', 'phone','address', 'word', 'acc_area','acc_opening_balance', 'acc_hold_balance', 'profile_image','nid_image','date'
    ];
    protected $casts = [
        'package_buy_id' => 'biginteger',
        'acc_name' => 'string',
        'acc_no' => 'biginteger',
        'email' => 'string',
        'phone' => 'biginteger',
        'address' => 'text',
        'word' => 'string',
        'acc_area' => 'string',
        'acc_opening_balance' => 'biginteger',
        'acc_hold_balance' => 'biginteger',
        'profile_image' => 'string',
        'nid_image' => 'string',
        'date' => 'date',
    ];
}

