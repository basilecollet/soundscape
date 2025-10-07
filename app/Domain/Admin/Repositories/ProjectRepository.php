<?php

namespace App\Domain\Admin\Repositories;

use App\Domain\Admin\Entities\Project;

interface ProjectRepository
{
    public function store(Project $project): void;
}
