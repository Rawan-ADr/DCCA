<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Secretaril extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'secretariats';
    protected $fillable = [
       'name',
       'email',
       'Phon',
       'password',
       'financial_portfolio',
       'address',
       'image'
    ];

    public function SecNotifications()
    {
        return $this->hasMany(SecNotification::class);
      }




  
}
