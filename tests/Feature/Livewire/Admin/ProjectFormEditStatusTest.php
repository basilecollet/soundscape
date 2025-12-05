<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Livewire\Admin\ProjectFormEdit;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe('Project Publishing', function () {
    test('can publish draft project with description', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Draft,
            'description' => 'Valid description for publishing',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('publish')
            ->assertHasNoErrors();

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Published);
    });

    test('cannot publish draft project without description', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Draft,
            'description' => null,
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('publish');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Draft);
    });

    test('cannot publish already published project', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Published,
            'description' => 'Valid description',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('publish');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Published);
    });

    test('cannot publish archived project', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Archived,
            'description' => 'Valid description',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('publish');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Archived);
    });

});

describe('Project Archiving', function () {
    test('can archive published project', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Published,
            'description' => 'Valid description',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('archive')
            ->assertHasNoErrors();

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Archived);
    });

    test('cannot archive draft project', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('archive');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Draft);
    });

    test('cannot archive already archived project', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Archived]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('archive');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Archived);
    });

    test('archive button only visible for published projects', function () {
        $publishedProject = Project::factory()->create(['status' => ProjectStatus::Published]);
        $draftProject = Project::factory()->create(['status' => ProjectStatus::Draft]);
        $archivedProject = Project::factory()->create(['status' => ProjectStatus::Archived]);

        Livewire::test(ProjectFormEdit::class, ['project' => $publishedProject])
            ->assertSee(__('admin.projects.actions.archive'));

        Livewire::test(ProjectFormEdit::class, ['project' => $draftProject])
            ->assertDontSee(__('admin.projects.actions.archive'));

        Livewire::test(ProjectFormEdit::class, ['project' => $archivedProject])
            ->assertDontSee(__('admin.projects.actions.archive'));
    });
});

describe('Project Drafting', function () {
    test('can set published project to draft', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Published,
            'description' => 'Valid description',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('draft')
            ->assertHasNoErrors();

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Draft);
    });

    test('can set archived project to draft', function () {
        $project = Project::factory()->create([
            'status' => ProjectStatus::Archived,
            'description' => 'Valid description',
        ]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('draft')
            ->assertHasNoErrors();

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Draft);
    });

    test('cannot set already draft project to draft', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->call('draft');

        $updatedProject = $project->fresh();
        expect($updatedProject)->not->toBeNull();
        expect($updatedProject?->status)->toBe(ProjectStatus::Draft);
    });

    test('draft button only visible for non-draft projects', function () {
        $publishedProject = Project::factory()->create(['status' => ProjectStatus::Published]);
        $archivedProject = Project::factory()->create(['status' => ProjectStatus::Archived]);
        $draftProject = Project::factory()->create(['status' => ProjectStatus::Draft]);

        Livewire::test(ProjectFormEdit::class, ['project' => $publishedProject])
            ->assertSee(__('admin.projects.actions.set_to_draft'));

        Livewire::test(ProjectFormEdit::class, ['project' => $archivedProject])
            ->assertSee(__('admin.projects.actions.set_to_draft'));

        Livewire::test(ProjectFormEdit::class, ['project' => $draftProject])
            ->assertDontSee(__('admin.projects.actions.set_to_draft'));
    });
});

describe('Status Badge Display', function () {
    test('displays draft badge for draft projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->assertSee(__('ui.status.draft'));
    });

    test('displays published badge for published projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Published]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->assertSee(__('ui.status.published'));
    });

    test('displays archived badge for archived projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Archived]);

        Livewire::test(ProjectFormEdit::class, ['project' => $project])
            ->assertSee(__('ui.status.archived'));
    });
});

describe('Computed Properties', function () {
    test('canPublish property returns true for draft projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canPublish'))->toBeTrue();
    });

    test('canPublish property returns false for published projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Published]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canPublish'))->toBeFalse();
    });

    test('canArchive property returns true for published projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Published]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canArchive'))->toBeTrue();
    });

    test('canArchive property returns false for draft projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canArchive'))->toBeFalse();
    });

    test('canDraft property returns true for published projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Published]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canDraft'))->toBeTrue();
    });

    test('canDraft property returns false for draft projects', function () {
        $project = Project::factory()->create(['status' => ProjectStatus::Draft]);

        $component = Livewire::test(ProjectFormEdit::class, ['project' => $project]);

        expect($component->get('canDraft'))->toBeFalse();
    });
});
