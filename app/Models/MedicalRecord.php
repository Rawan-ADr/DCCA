<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;
    
    protected $table = 'medical_records';
  protected $fillable = [

   'num_sessions',
   'first_sessions',
   'last_sessions',
   'doctor_id',
   'num_doctors',
   'patient_id'
];

      public function patient(){
        return $this->hasOne(Patient::class);
      }



}
