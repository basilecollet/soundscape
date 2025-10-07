<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withATitle(string $string): static
    {
        return $this->state(fn (array $attributes) => ['title' => $string]);
    }
}
