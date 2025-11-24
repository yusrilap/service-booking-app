<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Hair Cut',
                'Full Body Massage',
                'Facial Treatment',
                'Manicure',
            ]),
            'description' => fake()->sentence(10),
            'duration' => fake()->randomElement([30, 45, 60, 90]),
            'price' => fake()->randomElement([75000, 100000, 150000, 200000]),
            'is_active' => true,
        ];
    }
}

