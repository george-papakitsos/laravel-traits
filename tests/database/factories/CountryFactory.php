<?php

namespace Database\Factories;

use GPapakitsos\LaravelTraits\Tests\Models\Country;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

#[UseModel(Country::class)]
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'planet' => 'Earth',
            'ordering' => 1,
        ];
    }
}
