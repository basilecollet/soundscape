<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
});

test('authenticated user can access admin dashboard', function () {
    $response = $this->actingAs($this->admin)
        ->get('/admin');

    $response->assertOk();
    $response->assertViewIs('admin.dashboard');
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});