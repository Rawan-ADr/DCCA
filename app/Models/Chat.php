<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chats';
    protected $fillable = [
      
       'patient_id',
       'doctor_id'
    ];

    public function doctorMessages()
      {
          return $this->hasMany(DoctorMeaaage::class);
        }

        public function patientMessages()
      {
          return $this->hasMany(PatientMessage::class);
        }

}
