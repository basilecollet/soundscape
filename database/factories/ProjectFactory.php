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
        $words = fake()->unique()->words(3, true);
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

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Draft->value,
        ]);
    }

    public function withAProjectDate(Carbon $parse): static
    {
        return $this->state(fn (array $attributes) => [
            'project_date' => $parse,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Published->value,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ProjectStatus::Archived->value,
        ]);
    }

    public function withAShortDescription(string $string): static
    {
        return $this->state(fn (array $attributes) => [
            'short_description' => $string,
        ]);
    }
}
