<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
   
    protected $table = 'admin';
    protected $fillable = [
        'name',
        'image',
        'email',
        'Phon',
        'password',
        'financial_portfolio',
        'address'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];


}
