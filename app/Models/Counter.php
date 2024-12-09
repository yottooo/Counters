<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Counter extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'type',
        'type_id',
    ];

    public function kid(): HasOne {
        return $this->hasOne(Kid::class, 'id', 'type_id');
    }

    public function pregnancy(): HasOne {
        return $this->hasOne(Pregnancy::class, 'id', 'type_id');
    }

}
