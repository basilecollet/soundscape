<?php

use App\Domain\Admin\Entities\ValueObjects\BandcampPlayer;
use App\Domain\Admin\Exceptions\InvalidBandcampPlayerException;

test('it creates a bandcamp player from valid iframe', function () {
    $iframe = '<iframe style="border: 0; width: 400px; height: 340px;" src="https://bandcamp.com/EmbeddedPlayer/album=431442407" seamless></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->toString())->toBe($iframe)
        ->and($player->hasPlayer())->toBeTrue()
        ->and($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=431442407');
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
    $iframe = '  <iframe src="https://bandcamp.com/EmbeddedPlayer/album=123"></iframe>  ';

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
    $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=123"></iframe>';
    $player = BandcampPlayer::fromString($iframe);

    expect((string) $player)->toBe($iframe);
});

test('it converts empty player to empty string', function () {
    $player = BandcampPlayer::fromString(null);

    expect((string) $player)->toBe('');
});

test('it extracts src from valid iframe', function () {
    $iframe = '<iframe style="border: 0;" src="https://bandcamp.com/EmbeddedPlayer/album=431442407" seamless></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=431442407');
});

test('it extracts fallback link from iframe with anchor', function () {
    $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=431442407"><a href="https://artist.bandcamp.com/album/test">Test Album by Artist</a></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->getFallbackUrl())->toBe('https://artist.bandcamp.com/album/test')
        ->and($player->getFallbackText())->toBe('Test Album by Artist');
});

test('it returns null for fallback when no anchor present', function () {
    $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=431442407"></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    expect($player->getFallbackUrl())->toBeNull()
        ->and($player->getFallbackText())->toBeNull();
});

test('it accepts iframe with valid bandcamp embed URL even with extra attributes', function () {
    // DOMDocument strips dangerous event handlers, making the iframe safe
    $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=123" onload="alert(document.cookie)"></iframe>';

    $player = BandcampPlayer::fromString($iframe);

    // The key security feature: we only use getSrc() in the view, ignoring all other attributes
    expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=123')
        ->and($player->hasPlayer())->toBeTrue();
});

/** @phpstan-ignore-next-line */
test('it blocks XSS attempt with javascript in src', function () {
    $maliciousIframe = '<iframe src="javascript:alert(1)"></iframe>';

    BandcampPlayer::fromString($maliciousIframe);
})->throws(InvalidBandcampPlayerException::class);

/** @phpstan-ignore-next-line */
test('it blocks non-embed bandcamp URLs', function () {
    $iframe = '<iframe src="https://bandcamp.com/some-other-page"></iframe>';

    BandcampPlayer::fromString($iframe);
})->throws(InvalidBandcampPlayerException::class);
