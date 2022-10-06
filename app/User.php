<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'package_id','company_name', 'payment_type', 'account_no','duration', 'start_date','end_date', 'database_name', 'date','remember_token','password'
    ];




    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
        'date' => 'date',
        'password' => 'string',
    ];

    //Eloquest Orm Relationship
    //packages relation
    public function packages(){
        return $this->belongsTo(Package::class, 'package_id');
    }
}
