<?php

namespace Database\Factories;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
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
     * @return array{
     *     title: string,
     *     slug: string,
     *     status: string,
     *     description: ?string,
     *     short_description: ?string,
     *     client_name: ?string,
     *     project_date: ?Carbon,
     *     created_at: Carbon,
     *     updated_at: Carbon
     * }
     */
    public function definition(): array
    {
        $words = $this->faker->unique()->words(3, true);
        $title = is_string($words) ? $words : implode(' ', $words);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'status' => ProjectStatus::Draft->value,
            'description' => null,
            'short_description' => null,
            'client_name' => null,
            'project_date' => null,

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

    public function withDescription(string $string): static
    {
        return $this->state(fn (array $attributes) => [
            'description' => $string,
        ]);
    }
}
