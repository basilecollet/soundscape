<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PageContent>
 */
class PageContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'content' => $this->faker->paragraph(),
            'title' => $this->faker->sentence(3),
            'page' => $this->faker->randomElement(['home', 'about', 'contact']),
        ];
    }
}
