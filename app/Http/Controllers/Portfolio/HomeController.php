<?php

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Services\ContentService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ContentService $contentService
    ) {}

    public function __invoke(): View
    {
        $content = $this->contentService->getHomeContent();

        return view('portfolio.home', [
            'content' => $content,
            'seo' => [
                'title' => 'Soundscape Audio - Professional Audio Engineering',
                'description' => 'Professional audio engineering services including mixing, mastering, and sound design. Transform your audio projects with industry-standard expertise.',
                'keywords' => 'audio engineering, mixing, mastering, sound design, music production, audio professional',
            ],
        ]);
    }
}
