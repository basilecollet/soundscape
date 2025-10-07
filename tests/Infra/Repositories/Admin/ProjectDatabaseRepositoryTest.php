<?php

use App\Domain\Admin\Entities\Project;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can store a project from the domain entity', function () {
    $domainProject = Project::new('My project');

    $repository = new ProjectDatabaseRepository;

    $repository->store($domainProject);

    $this->assertDatabaseHas('projects', [
        'title' => 'My project',
    ]);
});
