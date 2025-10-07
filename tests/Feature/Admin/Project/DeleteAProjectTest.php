<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can delete a project if user is connected', function () {
    $project = Project::factory()->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware()
        ->delete(route('admin.project.destroy', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.index'));
});

test('delete a project from the database', function () {
    $project = Project::factory()->create();

    $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware()
        ->delete(route('admin.project.destroy', ['project' => $project->id]));

    $this->assertDatabaseMissing('projects', [
        'id' => $project->id,
    ]);
});
