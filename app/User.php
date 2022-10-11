<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'package_buy_id','name', 'email','role_as'
    ];




    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'package_buy_id' => 'biginteger',
        'name' => 'string',
        'email' => 'string',
        'role_as' => 'enum',
        'password' => 'string',
    ];

    //Eloquest Orm Relationship
    public function usepackage(){
        return $this->belongsTo(PackageBuy::class,'package_buy_id','id');

    }

}
