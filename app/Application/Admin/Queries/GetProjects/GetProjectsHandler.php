<?php

declare(strict_types=1);

namespace App\Application\Admin\Queries\GetProjects;

use App\Application\Admin\DTOs\ProjectData;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;
use Illuminate\Support\Collection;

final readonly class GetProjectsHandler
{
    public function __construct(
        private ProjectDatabaseRepository $repository,
    ) {}

    /**
     * @return Collection<int, ProjectData>
     */
    public function handle(): Collection
    {
        return $this->repository->getAll()
            ->map(fn ($project) => ProjectData::fromEntity($project));
    }
}
