<?php

declare(strict_types=1);

use App\Livewire\Admin\ProjectList;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('component renders successfully', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertStatus(200);
});

test('displays all projects in database', function () {
    $projects = Project::factory()->count(3)->create();

    /** @var Project $firstProject */
    $firstProject = $projects->get(0);
    /** @var Project $secondProject */
    $secondProject = $projects->get(1);
    /** @var Project $thirdProject */
    $thirdProject = $projects->get(2);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee($firstProject->title)
        ->assertSee($secondProject->title)
        ->assertSee($thirdProject->title);
});

test('displays project attributes correctly', function () {
    $project = Project::factory()->create([
        'title' => 'My Amazing Project',
        'slug' => 'my-amazing-project',
        'status' => 'draft',
        'client_name' => 'Acme Corporation',
        'project_date' => '2024-06-15',
    ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('My Amazing Project')
        ->assertSee('my-amazing-project')
        ->assertSee('Acme Corporation')
        ->assertSee('15 Jun 2024');
});

test('displays draft status badge', function () {
    Project::factory()->create([
        'status' => 'draft',
    ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('Draft');
});

test('displays published status badge', function () {
    Project::factory()->create([
        'status' => 'published',
    ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('Published');
});

test('displays archived status badge', function () {
    Project::factory()->create([
        'status' => 'archived',
    ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('Archived');
});

test('shows empty state when no projects exist', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('No projects found');
});

test('empty state has create project button', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectList::class)
        ->assertSee('Create Project');
});
