<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ClientName;

test('can create client name from string', function () {
    $clientName = ClientName::fromString('Acme Corporation');

    expect($clientName)->toBeInstanceOf(ClientName::class);
});

test('can convert client name to string', function () {
    $clientName = ClientName::fromString('Acme Corporation');

    expect((string) $clientName)->toBe('Acme Corporation');
});

test('client name trims whitespace', function () {
    $clientName = ClientName::fromString('  Client Name  ');

    expect((string) $clientName)->toBe('Client Name');
});

test('client name converts to title case', function () {
    $clientName = ClientName::fromString('acme corporation');

    expect((string) $clientName)->toBe('Acme Corporation');
});

test('client name converts uppercase to title case', function () {
    $clientName = ClientName::fromString('ACME CORPORATION');

    expect((string) $clientName)->toBe('Acme Corporation');
});

test('client name handles mixed case', function () {
    $clientName = ClientName::fromString('aCmE coRPoRaTion');

    expect((string) $clientName)->toBe('Acme Corporation');
});

test('client name handles accented characters', function () {
    $clientName = ClientName::fromString('café société');

    expect((string) $clientName)->toBe('Café Société');
});

test('client name handles special characters and accents', function () {
    $clientName = ClientName::fromString('société d\'étude');

    expect((string) $clientName)->toBe('Société D\'Étude');
});

test('client name handles multiple spaces', function () {
    $clientName = ClientName::fromString('acme   corporation');

    expect((string) $clientName)->toBe('Acme Corporation');
});

test('two client names with same content are equal', function () {
    $name1 = ClientName::fromString('Same Client');
    $name2 = ClientName::fromString('Same Client');

    expect($name1)->toEqual($name2);
});

test('it throws exception when client name is empty string', function () {
    expect(fn () => ClientName::fromString(''))
        ->toThrow(\App\Domain\Admin\Exceptions\InvalidClientNameException::class, 'Client name cannot be empty');
});

test('it throws exception when client name is only whitespace', function () {
    expect(fn () => ClientName::fromString('   '))
        ->toThrow(\App\Domain\Admin\Exceptions\InvalidClientNameException::class, 'Client name cannot be empty');
});

test('it throws exception when client name exceeds 255 characters', function () {
    $longName = str_repeat('a', 256);

    expect(fn () => ClientName::fromString($longName))
        ->toThrow(\App\Domain\Admin\Exceptions\InvalidClientNameException::class, 'Client name cannot exceed 255 characters');
});

test('it accepts client name with exactly 255 characters', function () {
    $name = str_repeat('a', 255);
    $clientName = ClientName::fromString($name);

    expect(mb_strlen((string) $clientName))->toBe(255);
});
