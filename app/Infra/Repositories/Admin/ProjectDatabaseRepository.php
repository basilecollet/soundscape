<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ClientName;
use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Repositories\ProjectRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Support\Collection;

class ProjectDatabaseRepository implements ProjectRepository
{
    public function store(Project $project): void
    {
        ProjectDatabase::create($project->toArray());
    }

    /**
     * @return Collection<int, Project>
     */
    public function getAll(): Collection
    {
        return ProjectDatabase::all()->map(function (ProjectDatabase $model) {
            return Project::reconstitute(
                title: ProjectTitle::fromString($model->title),
                slug: ProjectSlug::fromString($model->slug),
                status: ProjectStatus::from($model->status),
                description: $model->description !== null ? ProjectDescription::fromString($model->description) : null,
                shortDescription: $model->short_description !== null ? ProjectShortDescription::fromString($model->short_description) : null,
                clientName: $model->client_name !== null ? ClientName::fromString($model->client_name) : null,
                projectDate: $model->project_date !== null ? ProjectDate::fromString($model->project_date) : null,
            );
        });
    }
}
