<?php

namespace App\Application\Portfolio\Services;

use App\Domain\Portfolio\Entities\AboutPage;
use App\Domain\Portfolio\Entities\ContactPage;
use App\Domain\Portfolio\Entities\HomePage;
use App\Domain\Portfolio\Repositories\PageContentRepositoryInterface;
use App\Domain\Portfolio\Services\SectionVisibilityQueryInterface;
use App\Models\PageContent;

class ContentService
{
    public function __construct(
        private readonly SectionVisibilityQueryInterface $sectionVisibilityService,
        private readonly PageContentRepositoryInterface $pageContentRepository,
    ) {}

    /**
     * Get HomePage entity with validation logic
     */
    public function getHomePage(): HomePage
    {
        $fields = $this->pageContentRepository->getFieldsForPage('home');

        return HomePage::reconstitute($fields, $this->sectionVisibilityService);
    }

    /**
     * Get AboutPage entity with validation logic
     */
    public function getAboutPage(): AboutPage
    {
        $fields = $this->pageContentRepository->getFieldsForPage('about');

        return AboutPage::reconstitute($fields, $this->sectionVisibilityService);
    }

    /**
     * Get ContactPage entity with validation logic
     */
    public function getContactPage(): ContactPage
    {
        $fields = $this->pageContentRepository->getFieldsForPage('contact');

        return ContactPage::reconstitute($fields);
    }

    /**
     * Get home page content
     *
     * @return array{hero_title: string, hero_subtitle: string, hero_text: string, features?: array<int, array{title: string, description: string}>, show_features: bool, show_cta: bool}
     */
    public function getHomeContent(): array
    {
        $content = [
            // Hero section is always enabled
            'hero_title' => PageContent::getContentOrNull('home_hero_title') ?? '',
            'hero_subtitle' => PageContent::getContentOrNull('home_hero_subtitle') ?? '',
            'hero_text' => PageContent::getContentOrNull('home_hero_text') ?? '',
        ];

        // Check if features section is enabled
        $showFeatures = $this->sectionVisibilityService->isSectionEnabled('features', 'home');
        $content['show_features'] = $showFeatures;

        if ($showFeatures) {
            $content['features'] = [
                [
                    'title' => PageContent::getContentOrNull('home_feature_1_title') ?? '',
                    'description' => PageContent::getContentOrNull('home_feature_1_description') ?? '',
                ],
                [
                    'title' => PageContent::getContentOrNull('home_feature_2_title') ?? '',
                    'description' => PageContent::getContentOrNull('home_feature_2_description') ?? '',
                ],
                [
                    'title' => PageContent::getContentOrNull('home_feature_3_title') ?? '',
                    'description' => PageContent::getContentOrNull('home_feature_3_description') ?? '',
                ],
            ];
        }

        // Check if CTA section is enabled
        $content['show_cta'] = $this->sectionVisibilityService->isSectionEnabled('cta', 'home');

        return $content;
    }

    /**
     * Get about page content
     *
     * @return array{title: string, intro: string, bio: string, experience?: array{years: string, projects: string, clients: string}, services?: array<int, string>, philosophy?: string, show_experience: bool, show_services: bool, show_philosophy: bool}
     */
    public function getAboutContent(): array
    {
        $content = [
            // Hero and bio sections are always enabled
            'title' => PageContent::getContentOrNull('about_title') ?? '',
            'intro' => PageContent::getContentOrNull('about_intro') ?? '',
            'bio' => PageContent::getContentOrNull('about_bio') ?? '',
        ];

        // Check if experience section is enabled
        $showExperience = $this->sectionVisibilityService->isSectionEnabled('experience', 'about');
        $content['show_experience'] = $showExperience;

        if ($showExperience) {
            $content['experience'] = [
                'years' => PageContent::getContentOrNull('about_experience_years') ?? '',
                'projects' => PageContent::getContentOrNull('about_experience_projects') ?? '',
                'clients' => PageContent::getContentOrNull('about_experience_clients') ?? '',
            ];
        }

        // Check if services section is enabled
        $showServices = $this->sectionVisibilityService->isSectionEnabled('services', 'about');
        $content['show_services'] = $showServices;

        if ($showServices) {
            $content['services'] = [
                PageContent::getContentOrNull('about_service_1') ?? '',
                PageContent::getContentOrNull('about_service_2') ?? '',
                PageContent::getContentOrNull('about_service_3') ?? '',
                PageContent::getContentOrNull('about_service_4') ?? '',
                PageContent::getContentOrNull('about_service_5') ?? '',
                PageContent::getContentOrNull('about_service_6') ?? '',
            ];
        }

        // Check if philosophy section is enabled
        $showPhilosophy = $this->sectionVisibilityService->isSectionEnabled('philosophy', 'about');
        $content['show_philosophy'] = $showPhilosophy;

        if ($showPhilosophy) {
            $content['philosophy'] = PageContent::getContentOrNull('about_philosophy') ?? '';
        }

        return $content;
    }

    /**
     * Get contact page content
     *
     * @return array{title: string, subtitle: string, description: string, info: array{email: string, phone: string, location: string}}
     */
    public function getContactContent(): array
    {
        return [
            'title' => PageContent::getContentOrNull('contact_title') ?? '',
            'subtitle' => PageContent::getContentOrNull('contact_subtitle') ?? '',
            'description' => PageContent::getContentOrNull('contact_description') ?? '',
            'info' => [
                'email' => PageContent::getContentOrNull('contact_email') ?? '',
                'phone' => PageContent::getContentOrNull('contact_phone') ?? '',
                'location' => PageContent::getContentOrNull('contact_location') ?? '',
            ],
        ];
    }
}
