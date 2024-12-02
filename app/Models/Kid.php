<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'gender',
        'birthday',
    ];
}
