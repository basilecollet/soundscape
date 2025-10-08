<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{title: string, slug: string, created_at: Carbon, updated_at: Carbon}
     */
    public function definition(): array
    {
        $title = $this->faker->word();

        return [
            'title' => $title,
            'slug' => Str::slug($title),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withATitle(string $string): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $string,
            'slug' => Str::slug($string),
        ]);
    }
}
