<?php

namespace App\Application\Portfolio\Services;

use App\Models\PageContent;

class ContentService
{
    /**
     * Get home page content
     *
     * @return array{hero_title: string, hero_subtitle: string, hero_text: string, features: array<int, array{title: string, description: string}>}
     */
    public function getHomeContent(): array
    {
        return [
            'hero_title' => PageContent::getContent('home_hero_title', 'Soundscape Audio'),
            'hero_subtitle' => PageContent::getContent('home_hero_subtitle', 'Professional Audio Engineering'),
            'hero_text' => PageContent::getContent('home_hero_text', 'Transform your audio projects with industry-standard expertise. Professional mixing, mastering, and sound design services.'),
            'features' => [
                [
                    'title' => PageContent::getContent('home_feature_1_title', 'Mixing'),
                    'description' => PageContent::getContent('home_feature_1_description', 'Professional mixing services for all genres'),
                ],
                [
                    'title' => PageContent::getContent('home_feature_2_title', 'Mastering'),
                    'description' => PageContent::getContent('home_feature_2_description', 'Industry-standard mastering for your tracks'),
                ],
                [
                    'title' => PageContent::getContent('home_feature_3_title', 'Sound Design'),
                    'description' => PageContent::getContent('home_feature_3_description', 'Creative sound design for media projects'),
                ],
            ],
        ];
    }

    /**
     * Get about page content
     *
     * @return array{title: string, intro: string, bio: string, experience: array{years: string, projects: string, clients: string}, services: array<int, string>, philosophy: string}
     */
    public function getAboutContent(): array
    {
        return [
            'title' => PageContent::getContent('about_title', 'About Soundscape'),
            'intro' => PageContent::getContent('about_intro', 'Professional audio engineer with over 10 years of experience in music production and sound design.'),
            'bio' => PageContent::getContent('about_bio', 'Specializing in mixing, mastering, and sound design, I bring technical expertise and creative vision to every project. My approach combines industry-standard techniques with innovative solutions to deliver exceptional audio results.'),
            'experience' => [
                'years' => PageContent::getContent('about_experience_years', '10+'),
                'projects' => PageContent::getContent('about_experience_projects', '500+'),
                'clients' => PageContent::getContent('about_experience_clients', '200+'),
            ],
            'services' => [
                PageContent::getContent('about_service_1', 'Recording'),
                PageContent::getContent('about_service_2', 'Mixing'),
                PageContent::getContent('about_service_3', 'Mastering'),
                PageContent::getContent('about_service_4', 'Sound Design'),
                PageContent::getContent('about_service_5', 'Audio Restoration'),
                PageContent::getContent('about_service_6', 'Podcast Production'),
            ],
            'philosophy' => PageContent::getContent('about_philosophy', 'Every audio project tells a story. My mission is to enhance that story through precise technical execution and creative excellence.'),
        ];
    }

    /**
     * Get contact page content
     *
     * @return array{title: string, subtitle: string, description: string, info: array{email: string, phone: string, location: string}}
     */
    public function getContactContent(): array
    {
        return [
            'title' => PageContent::getContent('contact_title', 'Get in Touch'),
            'subtitle' => PageContent::getContent('contact_subtitle', 'Let\'s discuss your next audio project'),
            'description' => PageContent::getContent('contact_description', 'Ready to elevate your audio? Contact me to discuss your project requirements and discover how we can bring your vision to life with professional audio engineering.'),
            'info' => [
                'email' => PageContent::getContent('contact_email', 'contact@soundscape.audio'),
                'phone' => PageContent::getContent('contact_phone', '+33 6 XX XX XX XX'),
                'location' => PageContent::getContent('contact_location', 'Paris, France'),
            ],
        ];
    }
}
