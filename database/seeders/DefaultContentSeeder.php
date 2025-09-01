<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class DefaultContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // Home page content
            ['key' => 'home_hero_title', 'content' => 'Soundscape Audio', 'title' => 'Home - Hero Title', 'page' => 'home'],
            ['key' => 'home_hero_subtitle', 'content' => 'Professional Audio Engineering', 'title' => 'Home - Hero Subtitle', 'page' => 'home'],
            ['key' => 'home_hero_text', 'content' => 'Transform your audio projects with industry-standard expertise. Professional mixing, mastering, and sound design services.', 'title' => 'Home - Hero Text', 'page' => 'home'],

            // Home features
            ['key' => 'home_feature_1_title', 'content' => 'Mixing', 'title' => 'Home - Feature 1 Title', 'page' => 'home'],
            ['key' => 'home_feature_1_description', 'content' => 'Professional mixing services for all genres', 'title' => 'Home - Feature 1 Description', 'page' => 'home'],
            ['key' => 'home_feature_2_title', 'content' => 'Mastering', 'title' => 'Home - Feature 2 Title', 'page' => 'home'],
            ['key' => 'home_feature_2_description', 'content' => 'Industry-standard mastering for your tracks', 'title' => 'Home - Feature 2 Description', 'page' => 'home'],
            ['key' => 'home_feature_3_title', 'content' => 'Sound Design', 'title' => 'Home - Feature 3 Title', 'page' => 'home'],
            ['key' => 'home_feature_3_description', 'content' => 'Creative sound design for media projects', 'title' => 'Home - Feature 3 Description', 'page' => 'home'],

            // About page content
            ['key' => 'about_title', 'content' => 'About Soundscape', 'title' => 'About - Main Title', 'page' => 'about'],
            ['key' => 'about_intro', 'content' => 'Professional audio engineer with over 10 years of experience in music production and sound design.', 'title' => 'About - Intro', 'page' => 'about'],
            ['key' => 'about_bio', 'content' => 'Specializing in mixing, mastering, and sound design, I bring technical expertise and creative vision to every project. My approach combines industry-standard techniques with innovative solutions to deliver exceptional audio results.', 'title' => 'About - Biography', 'page' => 'about'],
            ['key' => 'about_philosophy', 'content' => 'Every audio project tells a story. My mission is to enhance that story through precise technical execution and creative excellence.', 'title' => 'About - Philosophy', 'page' => 'about'],

            // About experience
            ['key' => 'about_experience_years', 'content' => '10+', 'title' => 'About - Years Experience', 'page' => 'about'],
            ['key' => 'about_experience_projects', 'content' => '500+', 'title' => 'About - Projects Completed', 'page' => 'about'],
            ['key' => 'about_experience_clients', 'content' => '200+', 'title' => 'About - Happy Clients', 'page' => 'about'],

            // About services
            ['key' => 'about_service_1', 'content' => 'Recording', 'title' => 'About - Service 1', 'page' => 'about'],
            ['key' => 'about_service_2', 'content' => 'Mixing', 'title' => 'About - Service 2', 'page' => 'about'],
            ['key' => 'about_service_3', 'content' => 'Mastering', 'title' => 'About - Service 3', 'page' => 'about'],
            ['key' => 'about_service_4', 'content' => 'Sound Design', 'title' => 'About - Service 4', 'page' => 'about'],
            ['key' => 'about_service_5', 'content' => 'Audio Restoration', 'title' => 'About - Service 5', 'page' => 'about'],
            ['key' => 'about_service_6', 'content' => 'Podcast Production', 'title' => 'About - Service 6', 'page' => 'about'],

            // Contact page content
            ['key' => 'contact_title', 'content' => 'Get in Touch', 'title' => 'Contact - Main Title', 'page' => 'contact'],
            ['key' => 'contact_subtitle', 'content' => 'Let\'s discuss your next audio project', 'title' => 'Contact - Subtitle', 'page' => 'contact'],
            ['key' => 'contact_description', 'content' => 'Ready to elevate your audio? Contact me to discuss your project requirements and discover how we can bring your vision to life with professional audio engineering.', 'title' => 'Contact - Description', 'page' => 'contact'],
            ['key' => 'contact_email', 'content' => 'contact@soundscape.audio', 'title' => 'Contact - Email', 'page' => 'contact'],
            ['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'title' => 'Contact - Phone', 'page' => 'contact'],
            ['key' => 'contact_location', 'content' => 'Paris, France', 'title' => 'Contact - Location', 'page' => 'contact'],
        ];

        foreach ($contents as $content) {
            PageContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }

        $this->command->info('Default content seeded successfully.');
    }
}
