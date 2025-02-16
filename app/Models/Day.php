<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $fillable=[
        'day_name'
    ];

    public function doctors(){
        return $this->belongsToMany(Doctor::class,'doctor_days')
        ->withPivot('begin_consultation_time','end_consultation_time')
        ->withTimestamps();  

}
}