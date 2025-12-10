<?php

use App\Livewire\ContactForm;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('contact form renders successfully', function () {
    Livewire::test(ContactForm::class)
        ->assertStatus(200)
        ->assertSee('name')
        ->assertSee('email')
        ->assertSee('message');
});

test('contact form validates required fields', function () {
    Livewire::test(ContactForm::class)
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'message', 'gdpr_consent']);
});

test('contact form validates email format', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'invalid-email')
        ->set('message', 'Test message')
        ->set('gdpr_consent', true)
        ->call('submit')
        ->assertHasErrors(['email']);
});

test('contact form submits successfully with valid data', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Test Subject')
        ->set('message', 'This is a test message')
        ->set('gdpr_consent', true)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('subject', '')
        ->assertSet('message', '')
        ->assertSet('gdpr_consent', false);

    expect(ContactMessage::where('email', 'john@example.com')->exists())->toBeTrue();
});

test('contact form shows success message after submission', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('message', 'This is a test message')
        ->set('gdpr_consent', true)
        ->call('submit')
        ->assertSee('Thank you for your message');
});

test('contact form requires gdpr consent', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('message', 'This is a test message')
        ->set('gdpr_consent', false)
        ->call('submit')
        ->assertHasErrors(['gdpr_consent']);
});

test('contact form has ARIA live region for loading state announcements', function () {
    Livewire::test(ContactForm::class)
        ->assertSee('role="status"', false)
        ->assertSee('aria-live="polite"', false)
        ->assertSee('aria-atomic="true"', false);
});

test('contact form button has aria-busy attribute during loading', function () {
    Livewire::test(ContactForm::class)
        ->assertSee('wire:loading.attr="aria-busy=true"', false);
});
