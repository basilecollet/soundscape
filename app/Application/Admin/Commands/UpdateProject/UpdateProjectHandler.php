<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\UpdateProject;

use App\Application\Admin\DTOs\UpdateProjectData;
use App\Domain\Admin\Entities\ValueObjects\ClientName;
use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Repositories\ProjectRepository;

final readonly class UpdateProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(UpdateProjectData $data): void
    {
        $project = $this->repository->getBySlug(
            ProjectSlug::fromString($data->slug)
        );

        $project->update(
            title: ProjectTitle::fromString($data->title),
            description: $data->description !== null ? ProjectDescription::fromString($data->description) : null,
            shortDescription: $data->shortDescription !== null ? ProjectShortDescription::fromString($data->shortDescription) : null,
            clientName: $data->clientName !== null ? ClientName::fromString($data->clientName) : null,
            projectDate: $data->projectDate !== null ? ProjectDate::fromString($data->projectDate) : null,
        );

        $this->repository->store($project);
    }
}
