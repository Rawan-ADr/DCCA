<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor_Day extends Model
{
    use HasFactory;
    protected $fillable=[
        'doctor_id',
        'day_id',
        'begin_consultation_time',
        'end_consultation_time'
    ];

    

}
