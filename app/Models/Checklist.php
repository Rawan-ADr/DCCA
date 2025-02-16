<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;
    protected $table = 'checklists';

    public function doctor(){
        return $this->belongsTo(Doctor::class);
      }

      public function patient(){
        return $this->hasOne(Patient::class);
      }

     

      public function problems()
      {
          return $this->hasMany(Problem::class);
        }


}





