<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list projects in administration', function () {
    $response = $this
        ->actingAs(User::factory()->create())
        ->get(route('admin.project.index'));

    $response->assertOk()
        ->assertViewIs('admin.project.index');
});
