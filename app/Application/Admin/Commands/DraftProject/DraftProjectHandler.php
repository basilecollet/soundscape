<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\DraftProject;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Repositories\ProjectRepository;

final readonly class DraftProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(string $slug): void
    {
        $project = $this->repository->getBySlug(
            ProjectSlug::fromString($slug)
        );

        $project->draft();

        $this->repository->store($project);
    }
}
