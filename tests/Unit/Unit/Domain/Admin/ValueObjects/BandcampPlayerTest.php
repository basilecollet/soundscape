<?php

use App\Domain\Admin\Entities\ValueObjects\BandcampPlayer;
use App\Domain\Admin\Exceptions\InvalidBandcampPlayerException;

test('it creates a bandcamp player from valid iframe', function () {
    $iframe = '<iframe style="border: 0; width: 400px; height: 340px;" src="https://bandcamp.com/EmbeddedPlayer/album=431442407" seamless></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->toString())->toBe($iframe)
        ->and($player->hasPlayer())->toBeTrue();
});

test('it creates an empty bandcamp player from null', function () {
    $player = BandcampPlayer::fromString(null);

    expect($player->toString())->toBeNull()
        ->and($player->hasPlayer())->toBeFalse();
});

test('it creates an empty bandcamp player from empty string', function () {
    $player = BandcampPlayer::fromString('');

    expect($player->toString())->toBeNull()
        ->and($player->hasPlayer())->toBeFalse();
});

test('it trims whitespace from iframe code', function () {
    $iframe = '  <iframe src="https://bandcamp.com/test"></iframe>  ';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->toString())->toBe(trim($iframe));
});

/** @phpstan-ignore-next-line */
test('it throws exception when not an iframe', function () {
    BandcampPlayer::fromString('<div>Not an iframe</div>');
})->throws(InvalidBandcampPlayerException::class, 'Bandcamp player must be an iframe tag');

/** @phpstan-ignore-next-line */
test('it throws exception when not from bandcamp', function () {
    BandcampPlayer::fromString('<iframe src="https://youtube.com/embed/test"></iframe>');
})->throws(InvalidBandcampPlayerException::class, 'Bandcamp player must contain bandcamp.com domain');

/** @phpstan-ignore-next-line */
test('it throws exception when code is too long', function () {
    $longIframe = '<iframe src="https://bandcamp.com/">'.str_repeat('x', 10000).'</iframe>';

    BandcampPlayer::fromString($longIframe);
})->throws(InvalidBandcampPlayerException::class, 'Bandcamp player code cannot exceed');

test('it converts to string correctly', function () {
    $iframe = '<iframe src="https://bandcamp.com/test"></iframe>';
    $player = BandcampPlayer::fromString($iframe);

    expect((string) $player)->toBe($iframe);
});

test('it converts empty player to empty string', function () {
    $player = BandcampPlayer::fromString(null);

    expect((string) $player)->toBe('');
});
