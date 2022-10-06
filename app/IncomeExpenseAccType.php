<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeExpenseAccType extends Model
{
    protected $fillable = [
        'purchase_owner_id', 'income_exp_acc_type', 'status'
    ];
    protected $casts = [
        'purchase_owner_id' => 'integer',
        'income_exp_acc_type' => 'string',
        'status' => 'enum',
    ];
}
