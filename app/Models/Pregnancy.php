<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    // 280 days is the standard pregnancy
    public const LENGTH = 280;
    protected $fillable = [
        'termin_date',
        'parent_id',
    ];
}
