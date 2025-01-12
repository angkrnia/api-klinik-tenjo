<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_start',
        'time_end',
        'duration',
        'initial_conditions',
        'result',
        'note',
    ];
}
