<?php

namespace App\Application\Portfolio\Services;

class ContentService
{
    /**
     * Get home page content
     * @return array{hero_title: string, hero_subtitle: string, hero_text: string, features: array<int, array{title: string, description: string}>}
     */
    public function getHomeContent(): array
    {
        return [
            'hero_title' => 'Soundscape Audio',
            'hero_subtitle' => 'Professional Audio Engineering',
            'hero_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
            'features' => [
                [
                    'title' => 'Mixing',
                    'description' => 'Professional mixing services for all genres',
                ],
                [
                    'title' => 'Mastering',
                    'description' => 'Industry-standard mastering for your tracks',
                ],
                [
                    'title' => 'Sound Design',
                    'description' => 'Creative sound design for media projects',
                ],
            ],
        ];
    }

    /**
     * Get about page content
     * @return array{title: string, intro: string, bio: string, experience: array{years: string, projects: string, clients: string}, services: array<int, string>, philosophy: string}
     */
    public function getAboutContent(): array
    {
        return [
            'title' => 'About Soundscape',
            'intro' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.',
            'bio' => 'Sed posuere consectetur est at lobortis. Donec sed odio dui. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.',
            'experience' => [
                'years' => '10+',
                'projects' => '500+',
                'clients' => '200+',
            ],
            'services' => [
                'Recording',
                'Mixing',
                'Mastering',
                'Sound Design',
                'Audio Restoration',
                'Podcast Production',
            ],
            'philosophy' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur blandit tempus porttitor. Nullam quis risus eget urna mollis ornare vel eu leo.',
        ];
    }

    /**
     * Get contact page content
     * @return array{title: string, subtitle: string, description: string, info: array{email: string, phone: string, location: string}}
     */
    public function getContactContent(): array
    {
        return [
            'title' => 'Get in Touch',
            'subtitle' => 'Let\'s discuss your next audio project',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'info' => [
                'email' => 'contact@soundscape.audio',
                'phone' => '+33 6 XX XX XX XX',
                'location' => 'Paris, France',
            ],
        ];
    }
}
