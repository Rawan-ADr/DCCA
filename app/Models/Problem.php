<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;
    protected $table = 'problems';

    public function checklist(){
        return $this->belongsTo(Checklist::class);
      }
      public function therapy_session()
      {
          return $this->hasMany(TherapySession::class);
        }


}
