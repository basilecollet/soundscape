<?php

use App\Domain\Admin\Entities\Project;

test('create a project with the right data', function () {
    $project = Project::new('My project');

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->getTitle())->toBe('My project');
});
