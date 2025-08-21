<?php

namespace Database\Factories;

use App\Domain\Admin\Enums\ContentKeys;
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
        // Générer une clé unique en combinant une clé de l'enum avec un suffix aléatoire
        $page = $this->faker->randomElement(ContentKeys::getAvailablePages());
        $availableKeys = ContentKeys::getKeysForPage($page);
        $baseKey = $this->faker->randomElement($availableKeys);
        $key = $baseKey . '_' . $this->faker->unique()->randomNumber(4);

        return [
            'key' => $key,
            'content' => $this->faker->paragraph(),
            'title' => $this->faker->sentence(3),
            'page' => $page,
        ];
    }

    /**
     * Use exact enum keys for seeding
     */
    public function withExactKey(): static
    {
        return $this->state(function (array $attributes) {
            $page = $this->faker->randomElement(ContentKeys::getAvailablePages());
            $availableKeys = ContentKeys::getKeysForPage($page);
            $key = $this->faker->randomElement($availableKeys);

            return [
                'key' => $key,
                'title' => ContentKeys::getLabel($key),
                'page' => $page,
            ];
        });
    }
}
