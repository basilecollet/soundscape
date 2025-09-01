<?php

declare(strict_types=1);

namespace App\Domain\Admin\Enums;

enum ContentKeys: string
{
    // HOME PAGE
    case HOME_HERO_TITLE = 'home_hero_title';
    case HOME_HERO_SUBTITLE = 'home_hero_subtitle';
    case HOME_HERO_TEXT = 'home_hero_text';
    case HOME_FEATURE_1_TITLE = 'home_feature_1_title';
    case HOME_FEATURE_1_DESCRIPTION = 'home_feature_1_description';
    case HOME_FEATURE_2_TITLE = 'home_feature_2_title';
    case HOME_FEATURE_2_DESCRIPTION = 'home_feature_2_description';
    case HOME_FEATURE_3_TITLE = 'home_feature_3_title';
    case HOME_FEATURE_3_DESCRIPTION = 'home_feature_3_description';

    // ABOUT PAGE
    case ABOUT_TITLE = 'about_title';
    case ABOUT_INTRO = 'about_intro';
    case ABOUT_BIO = 'about_bio';
    case ABOUT_PHILOSOPHY = 'about_philosophy';
    case ABOUT_EXPERIENCE_YEARS = 'about_experience_years';
    case ABOUT_EXPERIENCE_PROJECTS = 'about_experience_projects';
    case ABOUT_EXPERIENCE_CLIENTS = 'about_experience_clients';
    case ABOUT_SERVICE_1 = 'about_service_1';
    case ABOUT_SERVICE_2 = 'about_service_2';
    case ABOUT_SERVICE_3 = 'about_service_3';
    case ABOUT_SERVICE_4 = 'about_service_4';
    case ABOUT_SERVICE_5 = 'about_service_5';
    case ABOUT_SERVICE_6 = 'about_service_6';

    // CONTACT PAGE
    case CONTACT_TITLE = 'contact_title';
    case CONTACT_SUBTITLE = 'contact_subtitle';
    case CONTACT_DESCRIPTION = 'contact_description';
    case CONTACT_EMAIL = 'contact_email';
    case CONTACT_PHONE = 'contact_phone';
    case CONTACT_LOCATION = 'contact_location';

    /**
     * Get all keys for a specific page
     *
     * @return array<string>
     */
    public static function getKeysForPage(string $page): array
    {
        return match ($page) {
            'home' => [
                self::HOME_HERO_TITLE->value,
                self::HOME_HERO_SUBTITLE->value,
                self::HOME_HERO_TEXT->value,
                self::HOME_FEATURE_1_TITLE->value,
                self::HOME_FEATURE_1_DESCRIPTION->value,
                self::HOME_FEATURE_2_TITLE->value,
                self::HOME_FEATURE_2_DESCRIPTION->value,
                self::HOME_FEATURE_3_TITLE->value,
                self::HOME_FEATURE_3_DESCRIPTION->value,
            ],
            'about' => [
                self::ABOUT_TITLE->value,
                self::ABOUT_INTRO->value,
                self::ABOUT_BIO->value,
                self::ABOUT_PHILOSOPHY->value,
                self::ABOUT_EXPERIENCE_YEARS->value,
                self::ABOUT_EXPERIENCE_PROJECTS->value,
                self::ABOUT_EXPERIENCE_CLIENTS->value,
                self::ABOUT_SERVICE_1->value,
                self::ABOUT_SERVICE_2->value,
                self::ABOUT_SERVICE_3->value,
                self::ABOUT_SERVICE_4->value,
                self::ABOUT_SERVICE_5->value,
                self::ABOUT_SERVICE_6->value,
            ],
            'contact' => [
                self::CONTACT_TITLE->value,
                self::CONTACT_SUBTITLE->value,
                self::CONTACT_DESCRIPTION->value,
                self::CONTACT_EMAIL->value,
                self::CONTACT_PHONE->value,
                self::CONTACT_LOCATION->value,
            ],
            default => [],
        };
    }

    /**
     * Get available pages
     *
     * @return array<string>
     */
    public static function getAvailablePages(): array
    {
        return ['home', 'about', 'contact'];
    }

    /**
     * Get all keys
     *
     * @return array<string>
     */
    public static function getAllKeys(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get human-readable label for a key
     */
    public static function getLabel(string $key): string
    {
        return match ($key) {
            // HOME PAGE
            self::HOME_HERO_TITLE->value => 'Hero Title',
            self::HOME_HERO_SUBTITLE->value => 'Hero Subtitle',
            self::HOME_HERO_TEXT->value => 'Hero Text',
            self::HOME_FEATURE_1_TITLE->value => 'Feature 1 Title',
            self::HOME_FEATURE_1_DESCRIPTION->value => 'Feature 1 Description',
            self::HOME_FEATURE_2_TITLE->value => 'Feature 2 Title',
            self::HOME_FEATURE_2_DESCRIPTION->value => 'Feature 2 Description',
            self::HOME_FEATURE_3_TITLE->value => 'Feature 3 Title',
            self::HOME_FEATURE_3_DESCRIPTION->value => 'Feature 3 Description',

            // ABOUT PAGE
            self::ABOUT_TITLE->value => 'Main Title',
            self::ABOUT_INTRO->value => 'Introduction',
            self::ABOUT_BIO->value => 'Biography',
            self::ABOUT_PHILOSOPHY->value => 'Philosophy',
            self::ABOUT_EXPERIENCE_YEARS->value => 'Years of Experience',
            self::ABOUT_EXPERIENCE_PROJECTS->value => 'Projects Completed',
            self::ABOUT_EXPERIENCE_CLIENTS->value => 'Happy Clients',
            self::ABOUT_SERVICE_1->value => 'Service 1',
            self::ABOUT_SERVICE_2->value => 'Service 2',
            self::ABOUT_SERVICE_3->value => 'Service 3',
            self::ABOUT_SERVICE_4->value => 'Service 4',
            self::ABOUT_SERVICE_5->value => 'Service 5',
            self::ABOUT_SERVICE_6->value => 'Service 6',

            // CONTACT PAGE
            self::CONTACT_TITLE->value => 'Contact Title',
            self::CONTACT_SUBTITLE->value => 'Subtitle',
            self::CONTACT_DESCRIPTION->value => 'Description',
            self::CONTACT_EMAIL->value => 'Email Address',
            self::CONTACT_PHONE->value => 'Phone Number',
            self::CONTACT_LOCATION->value => 'Location',

            default => ucwords(str_replace('_', ' ', $key)),
        };
    }

    /**
     * Validate if a key is valid for a page
     */
    public static function isValidKeyForPage(string $key, string $page): bool
    {
        return in_array($key, self::getKeysForPage($page), true);
    }
}
