<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Queries\GetPublishedProjects\GetPublishedProjectsHandler;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __construct(
        private readonly GetPublishedProjectsHandler $getPublishedProjectsHandler
    ) {}

    public function index(): View
    {
        $projects = $this->getPublishedProjectsHandler->handle();

        return view('portfolio.projects', [
            'projects' => $projects,
            'seo' => [
                'title' => 'Projects - Soundscape Audio',
                'description' => 'Explore our portfolio of professional audio engineering projects including mixing, mastering, and sound design work.',
                'keywords' => 'audio projects, portfolio, mixing projects, mastering projects, sound design work',
            ],
        ]);
    }
}