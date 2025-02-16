<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
  use HasApiTokens, Notifiable;
  protected $table = 'doctors';
  protected $fillable = [

   'name',
   'email',
   'Phon',
   'password',
   'image',
   'specialization',
   'financial_portfolio',
   'address',
   'percentage',
   'section_id'
];

    public function section(){
        return $this->belongsTo(Section::class);
      }


      public function checlists()
      {
          return $this->hasMany(Checklist::class);
        }

        public function appointments()
        {
            return $this->hasMany(Appointment::class);
          }

          public function therapy_sessions()
        {
            return $this->hasMany(TherapySession::class);
          }


          public function days(){
            return $this->belongsToMany(Day::class ,'doctor_days')
        ->withPivot('begin_consultation_time','end_consultation_time')
        ->withTimestamps();
          }

          public function DoctorNotifications()
          {
              return $this->hasMany(DoctorNotification::class);
            }


                     //إحصائيات

          // تابع لإرجاع اسم الطبيب وعدد الجلسات
    public function getDoctorWithSessionCount()
    {
        return [
            'doctor_name' => $this->name,
            'session_count' => $this->therapy_sessions()->count(),
        ];
    }

    
    // تابع لجلب جميع الأطباء مع عدد الجلسات
    public static function getAllDoctorsWithSessionCount()
    {
        return self::all()->map(function ($doctor) {
            return $doctor->getDoctorWithSessionCount();
        });
    }
         

         

        

}
