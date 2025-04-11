<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companies = Company::all();

        return [
           'type' => fake()->randomElement(['Full-Time','Part-Time','Remote','Internship']),
           'title' => fake()->jobTitle(),
           'description' => fake()->text(500),
           'salary' => fake()->randomElement(['Under $50K','$50K - $60K','$70K - $80K','$90K - $100K','$150K - $175K']),
           'location' => fake()->address(),
           'company_id' => fake()->randomElement($companies->pluck('id')->toArray()),
        ];
    }
}
