<?php

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Repositories\ProjectRepository;
use App\Models\Project as ProjectDatabase;

class ProjectDatabaseRepository implements ProjectRepository
{
    public function store(Project $project): void
    {
        ProjectDatabase::create($project->toArray());
    }
}
