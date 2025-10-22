<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Repositories;

use App\Domain\Portfolio\Entities\PublishedProject;
use Illuminate\Support\Collection;

interface ProjectRepository
{
    /**
     * Get all published projects ordered by date descending
     *
     * @return Collection<int, PublishedProject>
     */
    public function getAll(): Collection;
}
