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

test('two client names with same content are equal', function () {
    $name1 = ClientName::fromString('Same Client');
    $name2 = ClientName::fromString('Same Client');

    expect($name1)->toEqual($name2);
});
