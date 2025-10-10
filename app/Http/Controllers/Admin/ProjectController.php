<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Application\Admin\Queries\GetProjects\GetProjectsHandler;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private readonly GetProjectsHandler $getProjectsHandler,
    ) {}

    public function index(): View
    {
        $projects = $this->getProjectsHandler->handle();

        return view('admin.project.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.project.create');
    }

    public function edit(Project $project): View
    {
        return view('admin.project.edit', compact('project'));
    }

    public function destroy(string $project): RedirectResponse
    {
        $projectModel = Project::findOrFail($project);
        $projectModel->delete();

        return to_route('admin.project.index');
    }
}
