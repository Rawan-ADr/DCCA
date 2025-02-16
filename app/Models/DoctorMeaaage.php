<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMeaaage extends Model
{
    use HasFactory;
    protected $table = 'doctor_messages';
    protected $fillable = [
       'chat_id',
       'doctor_id',
       'message'
       
    ];
}
