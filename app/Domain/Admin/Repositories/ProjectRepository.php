<?php

declare(strict_types=1);

namespace App\Domain\Admin\Repositories;

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use Illuminate\Support\Collection;

interface ProjectRepository
{
    public function store(Project $project): void;

    /**
     * @return Collection<int, Project>
     */
    public function getAll(): Collection;

    public function findBySlug(ProjectSlug $slug): ?Project;
}
