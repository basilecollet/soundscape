<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can access a project add form if user is connected', function () {
    $project = Project::factory()->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('admin.project.edit', ['project' => $project->id]));

    $response->assertOk()
        ->assertViewIs('admin.project.edit');
});
