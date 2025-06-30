<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PageContent::create([
            'key' => 'home_text',
            'content' => 'Welcome to Soundscape Audio, your premier destination for high-quality audio experiences. We offer a wide range of products and services to enhance your sonic journey.',
            'title' => 'Home Text',
            'page' => 'home',
        ]);

        PageContent::create([
            'key' => 'about_section_1',
            'content' => 'At Soundscape Audio, we believe that sound is an essential part of human experience. Our mission is to provide the best audio solutions for all your needs.',
            'title' => 'About Us Text 1',
            'page' => 'about',
        ]);

        PageContent::create([
            'key' => 'about_section_2',
            'content' => 'Founded in 2025, Soundscape Audio has quickly become a leader in the audio industry. Our team of experts is dedicated to delivering exceptional sound quality.',
            'title' => 'About Us Text 2',
            'page' => 'about',
        ]);

        PageContent::create([
            'key' => 'about_section_3',
            'content' => 'We are committed to innovation and excellence in everything we do. From our products to our customer service, we strive to exceed your expectations.',
            'title' => 'About Us Text 3',
            'page' => 'about',
        ]);

        PageContent::create([
            'key' => 'contact_text',
            'content' => 'Have questions or need assistance? We\'d love to hear from you. Fill out the form below and our team will get back to you as soon as possible.',
            'title' => 'Contact Us',
            'page' => 'contact',
        ]);
    }
}
