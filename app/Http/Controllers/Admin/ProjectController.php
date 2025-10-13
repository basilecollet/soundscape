<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Application\Admin\Commands\ArchiveProject\ArchiveProjectHandler;
use App\Application\Admin\Commands\DeleteProject\DeleteProjectHandler;
use App\Application\Admin\Commands\DraftProject\DraftProjectHandler;
use App\Application\Admin\Commands\PublishProject\PublishProjectHandler;
use App\Application\Admin\Queries\GetProjects\GetProjectsHandler;
use App\Domain\Admin\Exceptions\ProjectCannotBeArchivedException;
use App\Domain\Admin\Exceptions\ProjectCannotBeDraftedException;
use App\Domain\Admin\Exceptions\ProjectCannotBePublishedException;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private readonly GetProjectsHandler $getProjectsHandler,
        private readonly DeleteProjectHandler $deleteProjectHandler,
        private readonly PublishProjectHandler $publishProjectHandler,
        private readonly ArchiveProjectHandler $archiveProjectHandler,
        private readonly DraftProjectHandler $draftProjectHandler,
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

    public function destroy(Project $project): RedirectResponse
    {
        $this->deleteProjectHandler->handle($project->slug);

        return to_route('admin.project.index');
    }

    public function publish(Project $project): RedirectResponse
    {
        try {
            $this->publishProjectHandler->handle($project->slug);

            return to_route('admin.project.edit', ['project' => $project->slug])
                ->with('success', 'Project published successfully.');
        } catch (ProjectCannotBePublishedException $e) {
            return to_route('admin.project.edit', ['project' => $project->slug])
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function archive(Project $project): RedirectResponse
    {
        try {
            $this->archiveProjectHandler->handle($project->slug);

            return to_route('admin.project.edit', ['project' => $project->slug])
                ->with('success', 'Project archived successfully.');
        } catch (ProjectCannotBeArchivedException $e) {
            return to_route('admin.project.edit', ['project' => $project->slug])
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function draft(Project $project): RedirectResponse
    {
        try {
            $this->draftProjectHandler->handle($project->slug);

            return to_route('admin.project.edit', ['project' => $project->id])
                ->with('success', 'Project set back to draft successfully.');
        } catch (ProjectCannotBeDraftedException $e) {
            return to_route('admin.project.edit', ['project' => $project->id])
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
