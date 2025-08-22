<?php

declare(strict_types=1);

use App\Models\PageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create();
});

test('admin can view content list', function () {
    // Créer quelques contenus
    PageContent::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)
        ->get('/admin/content');

    $response->assertOk();
    $response->assertViewIs('admin.content.index');
});

test('guest cannot access content management', function () {
    $response = $this->get('/admin/content');

    $response->assertRedirect('/login');
});

test('admin can access content edit page', function () {
    $content = PageContent::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get("/admin/content/{$content->id}/edit");

    $response->assertOk();
    $response->assertViewIs('admin.content.edit');
    $response->assertViewHas('contentId', $content->id);
});
