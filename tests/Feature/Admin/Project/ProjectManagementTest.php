<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list projects in administration if user is connected', function () {
    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('admin.project.index'));

    $response->assertOk()
        ->assertViewIs('admin.project.index');
});

test('list all projects in database', function () {
    Project::factory()->count(5)->create();

    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('admin.project.index'));

    $projects = $response->viewData('projects');

    expect($projects)->toHaveCount(5);
});
