<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fetus extends Model
{
    protected $fillable = [
        'pregnancy_id',
        'gender',
    ];
}
