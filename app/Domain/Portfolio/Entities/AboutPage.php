<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities;

use App\Domain\Admin\Services\SectionVisibilityServiceInterface;
use App\Domain\Portfolio\ValueObjects\PageField;

final class AboutPage extends PortfolioPage
{
    private const HERO_REQUIRED_FIELDS = [
        'about_title',
        'about_intro',
        'about_bio',
    ];

    private const EXPERIENCE_REQUIRED_FIELDS = [
        'about_experience_years',
        'about_experience_projects',
        'about_experience_clients',
    ];

    private const SERVICES_REQUIRED_FIELDS = [
        'about_service_1',
        'about_service_2',
        'about_service_3',
        'about_service_4',
        'about_service_5',
        'about_service_6',
    ];

    private const PHILOSOPHY_REQUIRED_FIELDS = ['about_philosophy'];

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
        // Hero + bio always required
        if (! $this->allFieldsHaveContent(self::HERO_REQUIRED_FIELDS)) {
            return false;
        }

        // Check optional sections
        if ($this->sectionVisibilityService->isSectionEnabled('experience', 'about')) {
            if (! $this->allFieldsHaveContent(self::EXPERIENCE_REQUIRED_FIELDS)) {
                return false;
            }
        }

        if ($this->sectionVisibilityService->isSectionEnabled('services', 'about')) {
            if (! $this->allFieldsHaveContent(self::SERVICES_REQUIRED_FIELDS)) {
                return false;
            }
        }

        if ($this->sectionVisibilityService->isSectionEnabled('philosophy', 'about')) {
            if (! $this->allFieldsHaveContent(self::PHILOSOPHY_REQUIRED_FIELDS)) {
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

        // Check conditional sections
        if ($this->sectionVisibilityService->isSectionEnabled('experience', 'about')) {
            foreach (self::EXPERIENCE_REQUIRED_FIELDS as $key) {
                if ($this->isFieldMissing($key)) {
                    $missing[] = $key;
                }
            }
        }

        if ($this->sectionVisibilityService->isSectionEnabled('services', 'about')) {
            foreach (self::SERVICES_REQUIRED_FIELDS as $key) {
                if ($this->isFieldMissing($key)) {
                    $missing[] = $key;
                }
            }
        }

        if ($this->sectionVisibilityService->isSectionEnabled('philosophy', 'about')) {
            foreach (self::PHILOSOPHY_REQUIRED_FIELDS as $key) {
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

    public function shouldShowExperience(): bool
    {
        return $this->sectionVisibilityService->isSectionEnabled('experience', 'about');
    }

    public function shouldShowServices(): bool
    {
        return $this->sectionVisibilityService->isSectionEnabled('services', 'about');
    }

    public function shouldShowPhilosophy(): bool
    {
        return $this->sectionVisibilityService->isSectionEnabled('philosophy', 'about');
    }
}
