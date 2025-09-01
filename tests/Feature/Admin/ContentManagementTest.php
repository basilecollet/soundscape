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

test('guest cannot access admin dashboard', function () {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});

test('admin can access dashboard', function () {
    $response = $this->actingAs($this->admin)->get('/admin');

    $response->assertOk()
        ->assertViewIs('admin.dashboard');
});

test('admin controller methods return correct views', function () {
    $controller = new \App\Http\Controllers\Admin\AdminController();
    
    // Test dashboard method
    $dashboardResponse = $controller->dashboard();
    expect($dashboardResponse)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($dashboardResponse->getName())->toBe('admin.dashboard');
    
    // Test content method
    $contentResponse = $controller->content();
    expect($contentResponse)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($contentResponse->getName())->toBe('admin.content.index');
    
    // Test editContent method
    $contentId = 123;
    $editResponse = $controller->editContent($contentId);
    expect($editResponse)->toBeInstanceOf(\Illuminate\View\View::class);
    expect($editResponse->getName())->toBe('admin.content.edit');
    expect($editResponse->getData()['contentId'])->toBe($contentId);
});
