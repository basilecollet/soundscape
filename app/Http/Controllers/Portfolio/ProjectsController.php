<?php

declare(strict_types=1);

namespace App\Http\Controllers\Portfolio;

use App\Application\Portfolio\Queries\GetPublishedProjectBySlug\GetPublishedProjectBySlugHandler;
use App\Application\Portfolio\Queries\GetPublishedProjects\GetPublishedProjectsHandler;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __construct(
        private readonly GetPublishedProjectsHandler $getPublishedProjectsHandler,
        private readonly GetPublishedProjectBySlugHandler $getPublishedProjectBySlugHandler
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

    public function show(Project $project): View
    {
        $projectData = $this->getPublishedProjectBySlugHandler->handle($project->slug);

        return view('portfolio.project-show', [
            'project' => $projectData,
            'seo' => [
                'title' => $projectData->title.' - Soundscape Audio',
                'description' => $projectData->shortDescription ?? substr($projectData->description, 0, 160),
                'keywords' => 'audio project, '.$projectData->title.', mixing, mastering, sound design',
            ],
        ]);
    }
}
