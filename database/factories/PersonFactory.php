<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Person;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->randomElement(['M','F','O']),
            'birth_date' => $this->faker->date('Y-m-d', '-20 years'),
            'death_date' => null,
            'document_type' => $this->faker->randomElement(['DNI','PASS','ID']),
            'document_id' => $this->faker->unique()->numerify('########'),
            'notes' => null,
        ];
    }
}
