<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorNotification extends Model
{
    use HasFactory;
    protected $table = 'doctor_notifications';
    protected $fillable = [
       'doctor_id',
       'message'
       
    ];
}
