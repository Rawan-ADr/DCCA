<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientNotification extends Model
{
    use HasFactory;
    protected $table = 'patient_notification';
    protected $fillable = [
       'patient_id',
       'message'
       
    ];
}
