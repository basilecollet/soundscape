<?php

declare(strict_types=1);

namespace App\Domain\Admin\Enums;

enum SectionKeys: string
{
    // HOME PAGE SECTIONS (désactivables)
    case HOME_FEATURES = 'features';
    case HOME_CTA = 'cta';

    // ABOUT PAGE SECTIONS (désactivables)
    case ABOUT_EXPERIENCE = 'experience';
    case ABOUT_SERVICES = 'services';
    case ABOUT_PHILOSOPHY = 'philosophy';

    /**
     * Get all disableable sections for a specific page
     *
     * @return array<string>
     */
    public static function getDisableableSectionsForPage(string $page): array
    {
        return match ($page) {
            'home' => [
                self::HOME_FEATURES->value,
                self::HOME_CTA->value,
            ],
            'about' => [
                self::ABOUT_EXPERIENCE->value,
                self::ABOUT_SERVICES->value,
                self::ABOUT_PHILOSOPHY->value,
            ],
            'contact' => [], // Aucune section désactivable sur la page contact
            default => [],
        };
    }

    /**
     * Get all available pages with disableable sections
     *
     * @return array<string>
     */
    public static function getAvailablePages(): array
    {
        return ['home', 'about'];
    }

    /**
     * Get all disableable section keys
     *
     * @return array<string>
     */
    public static function getAllDisableableSections(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get human-readable label for a section
     */
    public static function getLabel(string $sectionKey): string
    {
        return match ($sectionKey) {
            // HOME PAGE
            self::HOME_FEATURES->value => 'Features Section',
            self::HOME_CTA->value => 'Call to Action Section',

            // ABOUT PAGE
            self::ABOUT_EXPERIENCE->value => 'Experience Stats Section',
            self::ABOUT_SERVICES->value => 'Services Section',
            self::ABOUT_PHILOSOPHY->value => 'Philosophy Section',

            default => ucwords(str_replace('_', ' ', $sectionKey)),
        };
    }

    /**
     * Check if a section key is valid for a page
     */
    public static function isValidSectionForPage(string $sectionKey, string $page): bool
    {
        return in_array($sectionKey, self::getDisableableSectionsForPage($page), true);
    }
}
