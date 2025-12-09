<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Admin\Services\SectionVisibilityServiceInterface;
use App\Domain\Portfolio\ValueObjects\PageField;

final class HomePage extends PortfolioPage
{
    private const HERO_REQUIRED_FIELDS = [
        'home_hero_title',
        'home_hero_subtitle',
        'home_hero_text',
    ];

    private const FEATURES_REQUIRED_FIELDS = [
        'home_feature_1_title',
        'home_feature_1_description',
        'home_feature_2_title',
        'home_feature_2_description',
        'home_feature_3_title',
        'home_feature_3_description',
    ];

    /**
     * @param  array<PageField>  $fields
     */
    private function __construct(
        array $fields,
        private readonly SectionVisibilityServiceInterface $sectionVisibilityService,
    ) {
        parent::__construct($fields);
    }

    /**
     * @param  array<PageField>  $fields
     */
    public static function reconstitute(
        array $fields,
        SectionVisibilityServiceInterface $sectionVisibilityService
    ): self {
        return new self($fields, $sectionVisibilityService);
    }

    public function hasMinimumContent(): bool
    {
        // Hero section is always required
        if (! $this->allFieldsHaveContent(self::HERO_REQUIRED_FIELDS)) {
            return false;
        }

        // If features section is enabled, all fields must exist
        if ($this->sectionVisibilityService->isSectionEnabled('features', 'home')) {
            if (! $this->allFieldsHaveContent(self::FEATURES_REQUIRED_FIELDS)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array<string>
     */
    public function getMissingFields(): array
    {
        $missing = [];

        // Check hero fields
        foreach (self::HERO_REQUIRED_FIELDS as $key) {
            if ($this->isFieldMissing($key)) {
                $missing[] = $key;
            }
        }

        // Check features fields if section is enabled
        if ($this->sectionVisibilityService->isSectionEnabled('features', 'home')) {
            foreach (self::FEATURES_REQUIRED_FIELDS as $key) {
                if ($this->isFieldMissing($key)) {
                    $missing[] = $key;
                }
            }
        }

        return $missing;
    }

    private function isFieldMissing(string $key): bool
    {
        $field = $this->findField($key);

        return $field === null || $field->isEmpty();
    }

    public function shouldShowFeatures(): bool
    {
        return $this->sectionVisibilityService->isSectionEnabled('features', 'home');
    }

    public function shouldShowCta(): bool
    {
        return $this->sectionVisibilityService->isSectionEnabled('cta', 'home');
    }
}
