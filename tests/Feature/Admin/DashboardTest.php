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

test('dashboard displays content statistics', function () {
    // Créer quelques contenus de test
    \App\Models\PageContent::factory()->count(5)->create();

    $response = $this->actingAs($this->admin)
        ->get('/admin');

    $response->assertOk();
    $response->assertSeeText('Total Content');
    $response->assertSeeText('5'); // Le nombre de contenus créés
});

test('dashboard shows recent contact messages', function () {
    // Créer 3 messages de contact
    $messages = \App\Models\ContactMessage::factory()->count(3)->create([
        'read_at' => null, // Messages non lus
    ]);

    $response = $this->actingAs($this->admin)
        ->get('/admin');

    $response->assertOk();
    $response->assertSeeText('Recent Contact Messages');

    // Vérifier que les noms et sujets sont affichés
    foreach ($messages as $message) {
        $response->assertSeeText($message->name);
        if ($message->subject) {
            $response->assertSeeText($message->subject);
        }
    }
});
