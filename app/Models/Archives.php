<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archives extends Model
{
    use HasFactory;
    protected $table = 'archives';
    protected $fillable = [
       'name',
       'email',
       'Phon',
       'specialization',
       'address'
    ];


}
