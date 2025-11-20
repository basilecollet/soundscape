<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Queries\GetPublishedProjectBySlug;

use App\Application\Portfolio\DTOs\PublishedProjectDetailData;
use App\Domain\Portfolio\Repositories\ProjectRepository;

final readonly class GetPublishedProjectBySlugHandler
{
    public function __construct(
        private ProjectRepository $repository,
    ) {}

    public function handle(string $slug): PublishedProjectDetailData
    {
        $project = $this->repository->getBySlug($slug);

        return PublishedProjectDetailData::fromEntity($project);
    }
}
