<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable
{
  use HasApiTokens, Notifiable;
    protected $fillable = [
      'name',
      'email',
      'Phon',
      'password',
      'image',
      'financial_portfolio',
      'address',
  ];

    public function medical_record(){
        return $this->hasOne(MedicalRecord::class);
      }
      public function checklist(){
        return $this->hasOne(Checklist::class);
      }

      public function form(){
        return $this->hasOne(Form::class);
      }

      public function therapy_sessions()
      {
          return $this->hasMany(TherapySession::class);
        }

        public function appointments()
        {
            return $this->hasMany(Appointment::class);
          }

          public function patientNotifications()
          {
              return $this->hasMany(PatientNotification::class);
            }


              


}

