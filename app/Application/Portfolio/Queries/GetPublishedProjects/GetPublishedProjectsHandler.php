<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries\GetPublishedProjects;

use App\Application\Portfolio\DTOs\PublishedProjectData;
use App\Domain\Portfolio\Repositories\ProjectRepository;
use Illuminate\Support\Collection;

final readonly class GetPublishedProjectsHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    /**
     * @return Collection<int, PublishedProjectData>
     */
    public function handle(): Collection
    {
        return $this->repository->getAll()
            ->map(fn ($project) => PublishedProjectData::fromEntity($project));
    }
}
