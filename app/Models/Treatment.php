<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $table = 'treatments';
    protected $fillable = [
       'name',
       'price',
       'treatment_department_id'
    ];
    
    public function treatment()
        {
            return $this->belongsTo(TreatmentDepartment::class);
          }

          public function therapy_session()
      {
          return $this->hasMany(TherapySession::class);
        }
}
