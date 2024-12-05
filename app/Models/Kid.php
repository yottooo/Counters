<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kid extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'parent_id',
        'gender',
        'birthday',
    ];

    protected $appends = ['age'];

    public function getAgeAttribute(): int {
        return Carbon::parse($this->birthday)->age;
    }
}
