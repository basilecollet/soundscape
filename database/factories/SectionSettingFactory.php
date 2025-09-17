<?php

namespace Database\Factories;

use App\Models\SectionSetting;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<SectionSetting>
 */
class SectionSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{section_key: string, page: string, is_enabled: bool}
     */
    public function definition(): array
    {
        return [
            'section_key' => $this->faker->randomElement([
                'features', 'cta', 'experience', 'services', 'philosophy',
            ]),
            'page' => $this->faker->randomElement(['home', 'about', 'contact']),
            'is_enabled' => $this->faker->boolean(80), // 80% chance of being enabled
        ];
    }

    /**
     * Indicate that the section is enabled
     */
    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => true,
        ]);
    }

    /**
     * Indicate that the section is disabled
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_enabled' => false,
        ]);
    }

    /**
     * Set the page for the section setting
     */
    public function forPage(string $page): static
    {
        return $this->state(fn (array $attributes) => [
            'page' => $page,
        ]);
    }

    /**
     * Set the section key
     */
    public function forSection(string $sectionKey): static
    {
        return $this->state(fn (array $attributes) => [
            'section_key' => $sectionKey,
        ]);
    }
}
