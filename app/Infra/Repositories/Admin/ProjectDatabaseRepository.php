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
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Domain\Admin\Repositories\ProjectRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Support\Collection;

class ProjectDatabaseRepository implements ProjectRepository
{
    public function store(Project $project): void
    {
        ProjectDatabase::updateOrCreate(
            ['slug' => (string) $project->getSlug()],
            $project->toArray()
        );
    }

    /**
     * @return Collection<int, Project>
     */
    public function getAll(): Collection
    {
        return ProjectDatabase::all()->map(function (ProjectDatabase $model) {
            return $this->reconstituteProject($model);
        });
    }

    /**
     * @inheritdoc
     */
    public function findBySlug(ProjectSlug $slug): ?Project
    {
        $model = ProjectDatabase::where('slug', (string) $slug)->first();

        if ($model === null) {
            return null;
        }

        return $this->reconstituteProject($model);
    }

    /**
     * @inheritdoc
     */
    public function getBySlug(ProjectSlug $slug): Project
    {
        $project = $this->findBySlug($slug);

        if ($project === null) {
            throw ProjectNotFoundException::forSlug($slug);
        }

        return $project;
    }

    public function delete(ProjectSlug $slug): void
    {
        $deleted = ProjectDatabase::where('slug', (string) $slug)->delete();

        if ($deleted === 0) {
            throw ProjectNotFoundException::forSlug($slug);
        }
    }

    private function reconstituteProject(ProjectDatabase $projectDatabase): Project
    {
        return Project::reconstitute(
            title: ProjectTitle::fromString($projectDatabase->title),
            slug: ProjectSlug::fromString($projectDatabase->slug),
            status: ProjectStatus::from($projectDatabase->status),
            description: $projectDatabase->description !== null ? ProjectDescription::fromString($projectDatabase->description) : null,
            shortDescription: $projectDatabase->short_description !== null ? ProjectShortDescription::fromString($projectDatabase->short_description) : null,
            clientName: $projectDatabase->client_name !== null ? ClientName::fromString($projectDatabase->client_name) : null,
            projectDate: $projectDatabase->project_date !== null ? ProjectDate::fromString($projectDatabase->project_date) : null,
        );
    }
}
