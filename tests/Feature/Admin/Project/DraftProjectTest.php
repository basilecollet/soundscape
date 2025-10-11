<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can set published project back to draft', function () {
    $project = Project::factory()->create([
        'status' => 'published',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.draft', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
});

test('setting published project to draft updates status in database', function () {
    $project = Project::factory()->create([
        'status' => 'published',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.draft', ['project' => $project->id]));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'status' => 'draft',
    ]);
});

test('can set archived project back to draft', function () {
    $project = Project::factory()->create([
        'status' => 'archived',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.draft', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
});

test('cannot set draft project to draft', function () {
    $project = Project::factory()->create([
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.draft', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
    $response->assertSessionHasErrors();
});
