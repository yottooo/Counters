<?php

namespace Database\Factories;

use App\Models\Kid;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kid>
 */
class KidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Kid::class;

    public function definition(): array
    {
        // Retrieve a random parent (user) from the database or create one if needed
        $parentId = User::inRandomOrder()->first()->id;

        // Generate random data for the kid
        return [
            'name' => $this->faker->name, // Generate a random name
            'parent_id' => $parentId, // Randomly assign the parent ID
            'gender' => $this->faker->randomElement(['male', 'female']), // Randomly assign gender
            'birthday' => $this->faker->dateTimeBetween('-18 years', 'now')->format('Y-m-d'),
              ];
    }
}
