<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeExpensePaymentMethodType extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'income_exp_pay_method_type', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'income_exp_pay_method_type' => 'string',
        'status' => 'enum',
    ];
}
