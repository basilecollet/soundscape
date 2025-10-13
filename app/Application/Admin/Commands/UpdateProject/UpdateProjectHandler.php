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
use App\Domain\Admin\Services\StringNormalizationService;

final readonly class UpdateProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
        private StringNormalizationService $normalizer,
    ) {}

    public function handle(UpdateProjectData $data): void
    {
        $project = $this->repository->getBySlug(
            ProjectSlug::fromString($data->slug)
        );

        // Convert empty strings to null
        $description = $this->normalizer->normalizeToNullable($data->description);
        $shortDescription = $this->normalizer->normalizeToNullable($data->shortDescription);
        $clientName = $this->normalizer->normalizeToNullable($data->clientName);
        $projectDate = $this->normalizer->normalizeToNullable($data->projectDate);

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
