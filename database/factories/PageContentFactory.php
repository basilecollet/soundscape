<?php

namespace Database\Factories;

use App\Models\PageContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageContent>
 */
class PageContentFactory extends Factory
{
    protected $model = PageContent::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->words(2, true),
            'content' => $this->faker->paragraph(),
            'title' => $this->faker->sentence(3),
            'page' => $this->faker->randomElement(['home', 'about', 'contact']),
        ];
    }
}
