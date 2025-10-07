<?php

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Repositories\ProjectRepository;

class ProjectDatabaseRepository implements ProjectRepository
{
    public function store(Project $project): void
    {
        \App\Models\Project::create($project->toArray());
    }
}
