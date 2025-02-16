<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecNotification extends Model
{
    
    use HasFactory;
    protected $table = 'sec_notifications';
    protected $fillable = [
       'secretaril_id',
       'message'
       
    ];
}
