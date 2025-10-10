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

        // Convert empty strings to null
        $description = $data->description !== null && trim($data->description) !== '' ? $data->description : null;
        $shortDescription = $data->shortDescription !== null && trim($data->shortDescription) !== '' ? $data->shortDescription : null;
        $clientName = $data->clientName !== null && trim($data->clientName) !== '' ? $data->clientName : null;
        $projectDate = $data->projectDate !== null && trim($data->projectDate) !== '' ? $data->projectDate : null;

        $project->update(
            title: ProjectTitle::fromString($data->title),
            description: $description !== null ? ProjectDescription::fromString($description) : null,
            shortDescription: $shortDescription !== null ? ProjectShortDescription::fromString($shortDescription) : null,
            clientName: $clientName !== null ? ClientName::fromString($clientName) : null,
            projectDate: $projectDate !== null ? ProjectDate::fromString($projectDate) : null,
        );

        $this->repository->store($project);
    }
}
