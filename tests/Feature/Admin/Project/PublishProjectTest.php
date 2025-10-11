<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can publish a draft project', function () {
    $project = Project::factory()->create([
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.publish', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
});

test('publishing a draft project updates status in database', function () {
    $project = Project::factory()->create([
        'status' => 'draft',
    ]);

    $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.publish', ['project' => $project->id]));

    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'status' => 'published',
    ]);
});

test('cannot publish an already published project', function () {
    $project = Project::factory()->create([
        'status' => 'published',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.publish', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
    $response->assertSessionHasErrors();
});

test('cannot publish an archived project', function () {
    $project = Project::factory()->create([
        'status' => 'archived',
    ]);

    $response = $this
        ->actingAs(User::factory()->create())
        ->withoutMiddleware([ValidateCsrfToken::class])
        ->patch(route('admin.project.publish', ['project' => $project->id]));

    $response->assertRedirect(route('admin.project.edit', ['project' => $project->id]));
    $response->assertSessionHasErrors();
});
