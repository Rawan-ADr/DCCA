<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TherapySession extends Model
{
    use HasFactory;
    protected $table = 'therapy_sessions';
    protected $fillable = [
       'date',
       'status',
       'doctor_id',
       'patient_id',
       'treatment_id',
       'problem_id'
    ];

    public function doctor(){
        return $this->belongsto(Doctor::class);
      }

      public function patient(){
        return $this->hasOne(Patient::class);
      }

      public function problem()
      {
          return $this->belongsto(Problem::class);
        }


        public function treatment()
        {
            return $this->belongsTo(Treatment::class);
          }



                     /////statistics

       public static function countPatients()
       {
           return Patient::count();
       }
       
       
       public static function mostActiveDoctor()
       {
           return TherapySession::select('doctor_id', DB::raw('count(*) as total'))
                                ->groupBy('doctor_id')
                                ->orderBy('total', 'desc')
                                ->with('doctor:id,name')
                                ->first()
                                ->doctor;
       }
       

       public static function mostUsedTreatment()
    {
        return TherapySession::select('treatment_id', DB::raw('count(*) as total'))
            ->groupBy('treatment_id')
            ->orderBy('total', 'desc')
            ->with('treatment:id,name')
            ->first()
            ->treatment;
    }



}
