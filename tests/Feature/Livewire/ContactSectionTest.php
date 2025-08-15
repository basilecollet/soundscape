<?php

use App\Livewire\Components\ContactSection;
use App\Models\PageContent;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

/**
 * TDD Example: Contact Section Component Tests
 *
 * Ces tests suivent l'approche TDD:
 * 1. RED - Le test échoue car le bug existe
 * 2. GREEN - Corriger le bug pour faire passer le test
 * 3. REFACTOR - Améliorer le code si nécessaire
 */
test('contact section displays content from database', function () {
    // Arrange
    PageContent::create([
        'key' => 'contact_text',
        'content' => 'Please contact us for any inquiries',
        'title' => 'Contact Text',
        'page' => 'contact',
    ]);

    // Act & Assert
    Livewire::test(ContactSection::class)
        ->assertSee('Please contact us for any inquiries')
        ->assertDontSee('Contact us'); // Should not show default text
});

test('contact section shows default text when no content exists', function () {
    // Arrange: No content in database

    // Act & Assert
    Livewire::test(ContactSection::class)
        ->assertSee('Contact us')
        ->assertStatus(200);
});

test('contact form has all required fields', function () {
    // Act & Assert
    Livewire::test(ContactSection::class)
        ->assertSee('Name')
        ->assertSee('Email')
        ->assertSee('Message')
        ->assertSee('Send Message');
});

test('contact form validates required fields', function () {
    // This test is for future implementation
    $this->markTestIncomplete('Contact form submission not yet implemented');

    // Act & Assert
    Livewire::test(ContactSection::class)
        ->set('name', '')
        ->set('email', '')
        ->set('message', '')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message']);
});

test('contact form validates email format', function () {
    // This test is for future implementation
    $this->markTestIncomplete('Contact form submission not yet implemented');

    // Act & Assert
    Livewire::test(ContactSection::class)
        ->set('name', 'John Doe')
        ->set('email', 'invalid-email')
        ->set('message', 'Test message')
        ->call('submit')
        ->assertHasErrors(['email']);
});

test('contact form sends email on valid submission', function () {
    // This test is for future implementation
    $this->markTestIncomplete('Contact form submission not yet implemented');

    // Arrange
    Mail::fake();

    // Act
    Livewire::test('components.contact-section')
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('message', 'This is a test message')
        ->call('submit');

    // Assert
    Mail::assertSent(function ($mail) {
        return $mail->hasTo('admin@soundscape.com')
            && $mail->subject === 'New Contact Form Submission';
    });
});
