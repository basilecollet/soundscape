<?php

declare(strict_types=1);

use App\Application\Portfolio\DTOs\ContactFormData;

describe('ContactFormData DTO', function () {
    test('can be created with all required values', function () {
        $data = new ContactFormData(
            name: 'John Doe',
            email: 'john@example.com',
            subject: 'Test Subject',
            message: 'This is a test message',
            gdprConsent: true
        );

        expect($data->name)->toBe('John Doe');
        expect($data->email)->toBe('john@example.com');
        expect($data->subject)->toBe('Test Subject');
        expect($data->message)->toBe('This is a test message');
        expect($data->gdprConsent)->toBe(true);
    });

    test('can be created with null subject', function () {
        $data = new ContactFormData(
            name: 'Jane Doe',
            email: 'jane@example.com',
            subject: null,
            message: 'Message without subject',
            gdprConsent: false
        );

        expect($data->name)->toBe('Jane Doe');
        expect($data->email)->toBe('jane@example.com');
        expect($data->subject)->toBeNull();
        expect($data->message)->toBe('Message without subject');
        expect($data->gdprConsent)->toBe(false);
    });

    test('gdprConsent defaults to false when not provided', function () {
        $data = new ContactFormData(
            name: 'Test User',
            email: 'test@example.com',
            subject: null,
            message: 'Test message'
        );

        expect($data->gdprConsent)->toBe(false);
    });

    test('can be created from array with all values', function () {
        $array = [
            'name' => 'Alice Smith',
            'email' => 'alice@example.com',
            'subject' => 'Inquiry',
            'message' => 'I have a question',
            'gdpr_consent' => true,
        ];

        $data = ContactFormData::fromArray($array);

        expect($data->name)->toBe('Alice Smith');
        expect($data->email)->toBe('alice@example.com');
        expect($data->subject)->toBe('Inquiry');
        expect($data->message)->toBe('I have a question');
        expect($data->gdprConsent)->toBe(true);
    });

    test('can be created from array with missing optional values', function () {
        $array = [
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'message' => 'Simple message',
        ];

        $data = ContactFormData::fromArray($array);

        expect($data->name)->toBe('Bob Johnson');
        expect($data->email)->toBe('bob@example.com');
        expect($data->subject)->toBeNull();
        expect($data->message)->toBe('Simple message');
        expect($data->gdprConsent)->toBe(false);
    });

    test('can be converted to array with all values', function () {
        $data = new ContactFormData(
            name: 'Charlie Brown',
            email: 'charlie@example.com',
            subject: 'Project Inquiry',
            message: 'I would like to collaborate',
            gdprConsent: true
        );

        $array = $data->toArray();

        expect($array)->toBe([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
            'subject' => 'Project Inquiry',
            'message' => 'I would like to collaborate',
            'gdpr_consent' => true,
        ]);
    });

    test('can be converted to array with null subject', function () {
        $data = new ContactFormData(
            name: 'Diana Prince',
            email: 'diana@example.com',
            subject: null,
            message: 'No subject message',
            gdprConsent: false
        );

        $array = $data->toArray();

        expect($array)->toBe([
            'name' => 'Diana Prince',
            'email' => 'diana@example.com',
            'subject' => null,
            'message' => 'No subject message',
            'gdpr_consent' => false,
        ]);
    });

    test('roundtrip conversion preserves all data', function () {
        $original = [
            'name' => 'Eve Wilson',
            'email' => 'eve@example.com',
            'subject' => 'Roundtrip Test',
            'message' => 'Testing data integrity',
            'gdpr_consent' => true,
        ];

        $data = ContactFormData::fromArray($original);
        $converted = $data->toArray();

        expect($converted)->toBe($original);
    });

    test('roundtrip conversion preserves data with null subject', function () {
        $original = [
            'name' => 'Frank Castle',
            'email' => 'frank@example.com',
            'subject' => null,
            'message' => 'Message with null subject',
            'gdpr_consent' => false,
        ];

        $data = ContactFormData::fromArray($original);
        $converted = $data->toArray();

        expect($converted)->toBe($original);
    });
});
