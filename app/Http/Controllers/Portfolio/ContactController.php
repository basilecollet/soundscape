<?php

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService
    ) {}

    public function __invoke(): View
    {
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