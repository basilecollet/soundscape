<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can access a project add form if user is connected', function () {
    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('admin.project.create'));

    $response->assertOk()
        ->assertViewIs('admin.project.create');
});
