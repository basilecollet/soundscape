<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\ArchiveProject;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Repositories\ProjectRepository;

final readonly class ArchiveProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(string $slug): void
    {
        $project = $this->repository->getBySlug(
            ProjectSlug::fromString($slug)
        );

        $project->archive();

        $this->repository->store($project);
    }
}
