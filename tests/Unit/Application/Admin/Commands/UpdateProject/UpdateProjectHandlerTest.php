<?php

declare(strict_types=1);

use App\Application\Admin\Commands\UpdateProject\UpdateProjectHandler;
use App\Application\Admin\DTOs\UpdateProjectData;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can update project title', function () {
    $existingProject = Project::reconstitute(
        title: ProjectTitle::fromString('Original Title'),
        slug: ProjectSlug::fromString('original-title'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Draft
    );

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with(Mockery::on(fn ($slug) => (string) $slug === 'original-title'))
        ->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')->once()->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'original-title',
        'title' => 'Updated Title',
    ]);

    $handler->handle($data);

    expect((string) $existingProject->getTitle())->toBe('Updated Title');
});

test('can update all optional fields', function () {
    $existingProject = Project::reconstitute(
        title: ProjectTitle::fromString('Original Title'),
        slug: ProjectSlug::fromString('my-project'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Published
    );

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')->once()->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')->once()->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'Updated Title',
        'description' => 'New description',
        'short_description' => 'New short',
        'client_name' => 'New Client',
        'project_date' => '2024-12-25',
    ]);

    $handler->handle($data);

    expect((string) $existingProject->getTitle())->toBe('Updated Title')
        ->and((string) $existingProject->getDescription())->toBe('New description')
        ->and((string) $existingProject->getShortDescription())->toBe('New short')
        ->and((string) $existingProject->getClientName())->toBe('New Client')
        ->and($existingProject->getProjectDate()?->format('Y-m-d'))->toBe('2024-12-25');
});

test('can reset optional fields to null', function () {
    $existingProject = Project::new('My Project', 'Description', 'Short', 'Client', '2024-06-15');

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')->once()->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')->once()->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'My Project',
    ]);

    $handler->handle($data);

    expect($existingProject->getDescription())->toBeNull()
        ->and($existingProject->getShortDescription())->toBeNull()
        ->and($existingProject->getClientName())->toBeNull()
        ->and($existingProject->getProjectDate())->toBeNull();
});

test('handler calls repository to store updated project', function () {
    $existingProject = Project::new('Original');

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')->once()->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with(Mockery::on(fn ($project) => $project === $existingProject))
        ->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'original',
        'title' => 'Updated',
    ]);

    $handler->handle($data);
});

test('handler throws exception when project not found', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andThrow(new ProjectNotFoundException('Project with slug "non-existent" was not found.'));

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'non-existent',
        'title' => 'Some Title',
    ]);

    expect(fn () => $handler->handle($data))
        ->toThrow(ProjectNotFoundException::class);
});

test('updating project does not change slug', function () {
    $existingProject = Project::reconstitute(
        title: ProjectTitle::fromString('Original'),
        slug: ProjectSlug::fromString('original-slug'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Draft
    );

    $originalSlug = (string) $existingProject->getSlug();

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')->once()->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')->once()->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'original-slug',
        'title' => 'Completely Different Title',
    ]);

    $handler->handle($data);

    expect((string) $existingProject->getSlug())->toBe($originalSlug)
        ->and((string) $existingProject->getSlug())->toBe('original-slug');
});

test('updating project does not change status', function () {
    $existingProject = Project::reconstitute(
        title: ProjectTitle::fromString('Original'),
        slug: ProjectSlug::fromString('my-project'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Published
    );

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')->once()->andReturn($existingProject);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')->once()->andReturnNull();

    $handler = new UpdateProjectHandler($repository);

    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'Updated Title',
    ]);

    $handler->handle($data);

    expect($existingProject->getStatus()->isPublished())->toBeTrue();
});