<?php

declare(strict_types=1);

use App\Livewire\Admin\ProjectFormEdit;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('accepts valid bandcamp embed code', function () {
    $project = Project::factory()->create();
    $validEmbed = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=431442407"></iframe>';

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', $validEmbed)
        ->call('save')
        ->assertHasNoErrors('bandcampPlayer');

    $refreshedProject = $project->fresh();
    expect($refreshedProject)->not->toBeNull();

    /** @var Project $refreshedProject */
    expect($refreshedProject->bandcamp_player)->toBe($validEmbed);
});

test('accepts empty bandcamp player', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', '')
        ->call('save')
        ->assertHasNoErrors('bandcampPlayer');

    $refreshedProject = $project->fresh();
    expect($refreshedProject)->not->toBeNull();

    /** @var Project $refreshedProject */
    expect($refreshedProject->bandcamp_player)->toBeNull();
});

test('rejects invalid iframe tag', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', '<div>Not an iframe</div>')
        ->call('save')
        ->assertHasErrors('bandcampPlayer');
});

test('rejects non-bandcamp iframe', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', '<iframe src="https://youtube.com/embed/test"></iframe>')
        ->call('save')
        ->assertHasErrors('bandcampPlayer');
});

test('rejects javascript in src', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', '<iframe src="javascript:alert(1)"></iframe>')
        ->call('save')
        ->assertHasErrors('bandcampPlayer');
});

test('rejects non-embed bandcamp url', function () {
    $project = Project::factory()->create();

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', '<iframe src="https://bandcamp.com/some-page"></iframe>')
        ->call('save')
        ->assertHasErrors('bandcampPlayer');
});

test('rejects iframe code that is too long', function () {
    $project = Project::factory()->create();
    $longEmbed = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=123">'.str_repeat('x', 10000).'</iframe>';

    Livewire::test(ProjectFormEdit::class, ['project' => $project])
        ->set('bandcampPlayer', $longEmbed)
        ->call('save')
        ->assertHasErrors('bandcampPlayer');
});
