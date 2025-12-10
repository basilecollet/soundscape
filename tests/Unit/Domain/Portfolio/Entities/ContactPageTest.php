<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\ContactPage;
use App\Domain\Portfolio\ValueObjects\PageField;

test('contact page has minimum content with all fields', function () {
    // Given: All 6 contact fields exist
    $fields = [
        PageField::fromKeyAndContent('contact_title', 'Get in Touch'),
        PageField::fromKeyAndContent('contact_subtitle', 'Lets work together'),
        PageField::fromKeyAndContent('contact_description', 'Feel free to reach out'),
        PageField::fromKeyAndContent('contact_email', 'contact@example.com'),
        PageField::fromKeyAndContent('contact_phone', '+1234567890'),
        PageField::fromKeyAndContent('contact_location', 'San Francisco, CA'),
    ];

    $page = ContactPage::reconstitute($fields);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty();
});

test('contact page does not have minimum content with missing field', function () {
    // Given: Only 5 out of 6 contact fields exist
    $fields = [
        PageField::fromKeyAndContent('contact_title', 'Get in Touch'),
        PageField::fromKeyAndContent('contact_subtitle', 'Lets work together'),
        PageField::fromKeyAndContent('contact_description', 'Feel free to reach out'),
        PageField::fromKeyAndContent('contact_email', 'contact@example.com'),
        PageField::fromKeyAndContent('contact_phone', '+1234567890'),
        // contact_location is missing
    ];

    $page = ContactPage::reconstitute($fields);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toContain('contact_location')
        ->and($page->getMissingFields())->toHaveCount(1);
});

test('contact page does not have minimum content without any field', function () {
    // Given: No fields exist
    $fields = [];

    $page = ContactPage::reconstitute($fields);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toHaveCount(6);
});

test('contact page identifies empty fields as missing', function () {
    // Given: Fields exist but some are empty
    $fields = [
        PageField::fromKeyAndContent('contact_title', 'Get in Touch'),
        PageField::fromKeyAndContent('contact_subtitle', ''),  // Empty
        PageField::fromKeyAndContent('contact_description', 'Feel free to reach out'),
        PageField::fromKeyAndContent('contact_email', null),  // Null
        PageField::fromKeyAndContent('contact_phone', '+1234567890'),
        PageField::fromKeyAndContent('contact_location', '   '),  // Whitespace only
    ];

    $page = ContactPage::reconstitute($fields);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toContain('contact_subtitle')
        ->and($page->getMissingFields())->toContain('contact_email')
        ->and($page->getMissingFields())->toContain('contact_location')
        ->and($page->getMissingFields())->toHaveCount(3);
});

test('contact page missing multiple fields', function () {
    // Given: Only 2 fields exist
    $fields = [
        PageField::fromKeyAndContent('contact_title', 'Get in Touch'),
        PageField::fromKeyAndContent('contact_email', 'contact@example.com'),
    ];

    $page = ContactPage::reconstitute($fields);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toHaveCount(4)
        ->and($page->getMissingFields())->toContain('contact_subtitle')
        ->and($page->getMissingFields())->toContain('contact_description')
        ->and($page->getMissingFields())->toContain('contact_phone')
        ->and($page->getMissingFields())->toContain('contact_location');
});
