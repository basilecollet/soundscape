<?php

declare(strict_types=1);

use App\Livewire\Admin\ProjectFormEdit;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('component renders in edit mode with existing project data', function () {
    $project = Project::factory()
        ->withATitle('Existing Project')
        ->create([
            'description' => 'Full description',
            'short_description' => 'Short',
            'client_name' => 'Client Corp',
            'project_date' => '2024-06-15',
        ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->assertSet('title', 'Existing Project')
        ->assertSet('description', 'Full description')
        ->assertSet('shortDescription', 'Short')
        ->assertSet('clientName', 'Client Corp')
        ->assertSet('projectDate', '2024-06-15')
        ->assertStatus(200);
});

test('can update project with all fields', function () {
    $project = Project::factory()
        ->withATitle('Original Title')
        ->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Updated Title')
        ->set('description', 'Updated description')
        ->set('shortDescription', 'Updated short')
        ->set('clientName', 'Updated Client')
        ->set('projectDate', '2024-07-20')
        ->call('save')
        ->assertHasNoErrors();

    $project->refresh();
    expect($project->title)->toBe('Updated Title')
        ->and($project->description)->toBe('Updated description')
        ->and($project->short_description)->toBe('Updated short')
        ->and($project->client_name)->toBe('Updated Client')
        ->and($project->project_date->format('Y-m-d'))->toBe('2024-07-20');
});

test('can clear optional fields when editing', function () {
    $project = Project::factory()
        ->withATitle('Project With Data')
        ->create([
            'description' => 'Original description',
            'short_description' => 'Original short',
            'client_name' => 'Original Client',
            'project_date' => '2024-06-15',
        ]);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Updated Title')
        ->set('description', '')
        ->set('shortDescription', '')
        ->set('clientName', '')
        ->set('projectDate', '')
        ->call('save')
        ->assertHasNoErrors();

    $project->refresh();
    expect($project->description)->toBeNull()
        ->and($project->short_description)->toBeNull()
        ->and($project->client_name)->toBeNull()
        ->and($project->project_date)->toBeNull();
});

test('edit mode validation still works', function () {
    $project = Project::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

test('redirects back to edit page after successful update', function () {
    $project = Project::factory()
        ->withATitle('Test Project')
        ->create();

    $component = Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Updated Project')
        ->call('save');

    $component->assertRedirect(route('admin.project.edit', ['project' => $project->slug]));
});

test('edit mode shows success message', function () {
    $project = Project::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Updated Title')
        ->call('save');

    expect(session('success'))->toBe('Project updated successfully.');
});

test('edit mode validates short description max length', function () {
    $project = Project::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Valid Title')
        ->set('shortDescription', str_repeat('a', 501))
        ->call('save')
        ->assertHasErrors(['shortDescription']);
});

test('edit mode validates project date format', function () {
    $project = Project::factory()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('title', 'Valid Title')
        ->set('projectDate', 'invalid-date')
        ->call('save')
        ->assertHasErrors(['projectDate']);
});
