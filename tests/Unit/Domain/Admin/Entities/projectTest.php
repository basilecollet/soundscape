<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

test('create a project with the right data', function () {
    $project = Project::new('My project');

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->getTitle())->toBeInstanceOf(ProjectTitle::class)
        ->and($project->getTitle())->toEqual(ProjectTitle::fromString('My project'));
});
