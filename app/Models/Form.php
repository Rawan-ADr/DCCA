<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $fillable = [
      'age',
      'job',
      'gender',
      'diabetes',
      'kidney problems',
      'pressure',
      'heart',
      'allergic',
      'blood thinning',
      'epidemic liver',
      'thyroid',
      'cancer',
      'rheumatic',
      'another illnesses',
      'smoked',
      'pregnant',
      'pharmaceutical',
      'first visit to doctor',
  ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

      

}
