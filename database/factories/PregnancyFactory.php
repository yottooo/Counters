<?php

namespace Database\Factories;

use App\Models\Pregnancy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pregnancy~>
 */
class PregnancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Pregnancy::class;
    public function definition()
    {
        // Retrieve a random parent (user) from the database or create one if needed
        $parentId = User::inRandomOrder()->first()->id;

        return [
            'termin_date' => Carbon::now()->addDays(rand(1, 280)),
            'parent_id' => $parentId, // Dynamic parent ID
        ];
    }
}
