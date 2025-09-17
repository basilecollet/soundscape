<?php

namespace Database\Seeders;

use App\Domain\Admin\Enums\SectionKeys;
use App\Models\SectionSetting;
use Illuminate\Database\Seeder;

class DefaultSectionSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            // HOME PAGE SECTIONS - all enabled by default
            ['section_key' => SectionKeys::HOME_FEATURES->value, 'page' => 'home', 'is_enabled' => true],
            ['section_key' => SectionKeys::HOME_CTA->value, 'page' => 'home', 'is_enabled' => true],

            // ABOUT PAGE SECTIONS - all enabled by default
            ['section_key' => SectionKeys::ABOUT_EXPERIENCE->value, 'page' => 'about', 'is_enabled' => true],
            ['section_key' => SectionKeys::ABOUT_SERVICES->value, 'page' => 'about', 'is_enabled' => true],
            ['section_key' => SectionKeys::ABOUT_PHILOSOPHY->value, 'page' => 'about', 'is_enabled' => true],
        ];

        foreach ($sections as $section) {
            SectionSetting::updateOrCreate(
                [
                    'section_key' => $section['section_key'],
                    'page' => $section['page'],
                ],
                ['is_enabled' => $section['is_enabled']]
            );
        }
    }
}
