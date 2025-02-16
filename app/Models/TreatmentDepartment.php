<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentDepartment extends Model
{
    use HasFactory;
    protected $table = 'treatment_departments';

    public function treatments(){
        return $this->hasMany(Treatment::class);
      }

      
}
