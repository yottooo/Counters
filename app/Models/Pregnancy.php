<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Pregnancy extends Model
{
    use HasFactory;
    // 280 days is the standard pregnancy
    public const LENGTH = 280;
    protected $fillable = [
        'termin_date',
        'parent_id',
    ];

    public function fetuses(): HasMany {
        return $this->hasMany(Fetus::class, 'pregnancy_id', 'id');
    }

    /**
     * Accessor to calculate days left to termin_date.
     *
     * @return int|null
     */
    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->termin_date) {
            return null;
        }

        $terminDate = Carbon::parse($this->termin_date);
        $currentDate = Carbon::now();

        // Calculate the difference in days and ensure no negative values
        $daysLeft = $currentDate->diffInDays($terminDate, false);

        return $daysLeft ;
    }

}
