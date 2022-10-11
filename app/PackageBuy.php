<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageBuy extends Model
{
    protected $fillable = [
        'name', 'email', 'package_id','company_name', 'payment_type', 'account_no','duration', 'start_date','end_date', 'database_name','password', 'date'
    ];
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'package_id' => 'integer',
        'company_name' => 'string',
        'payment_type' => 'string',
        'account_no' => 'text',
        'duration' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'database_name' => 'string',
        'password' => 'string',
        'date' => 'date',
    ];

    //Eloquest Orm Relationship
    //packages relation
    public function packages(){
        return $this->belongsTo(Package::class, 'package_id');
    }
}
