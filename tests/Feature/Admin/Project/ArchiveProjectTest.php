<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can archive a published project', function () {
    $project = Project::factory()->create([
        'status' => 'published',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.archive', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
});

test('archiving a published project updates status in database', function () {
    $project = Project::factory()->create([
        'status' => 'published',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.archive', ['project' => $project->id]));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'status' => 'archived',
    ]);
});

test('cannot archive a draft project', function () {
    $project = Project::factory()->create([
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.archive', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
    $response->assertSessionHasErrors();
});

test('cannot archive an already archived project', function () {
    $project = Project::factory()->create([
        'status' => 'archived',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.archive', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
    $response->assertSessionHasErrors();
});
