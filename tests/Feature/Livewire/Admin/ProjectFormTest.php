<?php

declare(strict_types=1);

use App\Livewire\Admin\ProjectForm;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('component renders successfully', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->assertStatus(200);
});

test('title field is required', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

test('can create project with title only', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'My New Project')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    expect(Project::where('title', 'My New Project')->exists())->toBeTrue();
});

test('can create project with all fields', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Complete Project')
        ->set('description', 'Full description here')
        ->set('shortDescription', 'Short desc')
        ->set('clientName', 'Acme Corp')
        ->set('projectDate', '2024-06-15')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $project = Project::where('title', 'Complete Project')->firstOrFail();
    expect($project)->not->toBeNull()
        ->and($project->description)->toBe('Full description here')
        ->and($project->short_description)->toBe('Short desc')
        ->and($project->client_name)->toBe('Acme Corp')
        ->and($project->project_date)->toBeInstanceOf(Carbon::class)
        ->and($project->project_date?->format('Y-m-d'))->toBe('2024-06-15');
});

test('empty optional fields are saved as null', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Minimal Project')
        ->set('description', '')
        ->set('shortDescription', '')
        ->set('clientName', '')
        ->set('projectDate', '')
        ->call('save')
        ->assertHasNoErrors();

    $project = Project::where('title', 'Minimal Project')->firstOrFail();
    expect($project)->not->toBeNull()
        ->and($project->description)->toBeNull()
        ->and($project->short_description)->toBeNull()
        ->and($project->client_name)->toBeNull()
        ->and($project->project_date)->toBeNull();
});

test('redirects to edit page after successful creation', function () {
    $component = Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Redirect Test Project')
        ->call('save');

    $project = Project::where('title', 'Redirect Test Project')->firstOrFail();

    $component->assertRedirect(route('admin.project.edit', ['project' => $project->slug]));
});

test('shows error when project with same slug already exists', function () {
    Project::factory()
        ->withATitle('Existing Project')
        ->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Existing Project')
        ->call('save')
        ->assertHasErrors();
});

test('validates project date format', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Date Test')
        ->set('projectDate', 'invalid-date')
        ->call('save')
        ->assertHasErrors(['projectDate']);
});

test('validates short description max length', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Length Test')
        ->set('shortDescription', str_repeat('a', 501))
        ->call('save')
        ->assertHasErrors(['shortDescription']);
});

test('validates client name max length', function () {
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectForm::class)
        ->set('title', 'Length Test')
        ->set('clientName', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['clientName']);
});
