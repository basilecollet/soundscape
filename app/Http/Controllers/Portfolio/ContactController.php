<?php

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService,
    ) {}

    public function __invoke(): View
    {
        // Check if minimum content exists using domain entity
        $contactPage = $this->contentService->getContactPage();

        if (! $contactPage->hasMinimumContent()) {
            return view('portfolio.empty-state', [
                'title' => __('portfolio.empty_state.contact.title'),
                'description' => __('portfolio.empty_state.contact.description'),
                'seo' => [
                    'title' => 'Contact Soundscape - Under Construction',
                    'description' => 'This page is currently under construction.',
                    'keywords' => 'contact audio engineer',
                ],
            ]);
        }

        $content = $this->contentService->getContactContent();

        return view('portfolio.contact', [
            'content' => $content,
            'seo' => [
                'title' => 'Contact Soundscape - Get in Touch',
                'description' => 'Get in touch for professional audio engineering services. Contact us for mixing, mastering, sound design, and audio production projects.',
                'keywords' => 'contact audio engineer, audio services quote, mixing mastering contact, sound design inquiry',
            ],
        ]);
    }
}
