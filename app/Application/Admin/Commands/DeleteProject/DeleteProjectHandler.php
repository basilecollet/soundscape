<?php

declare(strict_types=1);

namespace App\Application\Admin\Commands\DeleteProject;

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Repositories\ProjectRepository;

final readonly class DeleteProjectHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(string $slug): void
    {
        $this->repository->delete(
            ProjectSlug::fromString($slug)
        );
    }
}
