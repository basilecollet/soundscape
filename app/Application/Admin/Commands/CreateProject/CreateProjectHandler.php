<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\CreateProject;

use App\Application\Admin\DTOs\CreateProjectData;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Exceptions\DuplicateProjectSlugException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

final readonly class CreateProjectHandler
{
    public function __construct(
        private ProjectDatabaseRepository $repository,
    ) {}

    public function handle(CreateProjectData $data): ProjectSlug
    {
        $project = Project::new(
            title: $data->title,
            description: $data->description,
            shortDescription: $data->shortDescription,
            clientName: $data->clientName,
            projectDate: $data->projectDate,
        );

        $existingProject = $this->repository->findBySlug($project->getSlug());

        if ($existingProject !== null) {
            throw DuplicateProjectSlugException::forSlug($project->getSlug());
        }

        $this->repository->store($project);

        return $project->getSlug();
    }
}
