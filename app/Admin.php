<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{

    use Notifiable;

    protected $guard = "admin";
    protected $fillable = [
        'email','phone','role_as','password'
    ];
    protected $hidden =[
        'password',
    ];
}

