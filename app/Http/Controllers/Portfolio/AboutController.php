<?php

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService
    ) {}

    public function __invoke(): View
    {
        $content = $this->contentService->getAboutContent();

        return view('portfolio.about', [
            'content' => $content,
            'seo' => [
                'title' => 'About Soundscape - Professional Audio Engineering',
                'description' => 'Learn about our professional audio engineering expertise and experience. Discover our approach to mixing, mastering, and sound design.',
                'keywords' => 'about audio engineer, sound design experience, mixing mastering expertise, audio production portfolio',
            ],
        ]);
    }
}
