<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMessage extends Model
{
    use HasFactory;
    protected $table = 'patient__messages';
    protected $fillable = [
       'chat_id',
       'patient_id',
       'message'
       
    ];
}
