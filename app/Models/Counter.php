<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'type_id',
    ];
}
