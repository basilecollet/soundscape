<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\ValueObjects\BandcampPlayer;

describe('BandcampPlayer Value Object (Portfolio - Read-only)', function () {
    test('can create from null value', function () {
        $player = BandcampPlayer::fromString(null);

        expect($player->hasPlayer())->toBeFalse();
        expect($player->toString())->toBeNull();
        expect($player->getSrc())->toBeNull();
        expect($player->getFallbackUrl())->toBeNull();
        expect($player->getFallbackText())->toBeNull();
        expect($player->__toString())->toBe('');
    });

    test('can create from empty string', function () {
        $player = BandcampPlayer::fromString('');

        expect($player->hasPlayer())->toBeFalse();
        expect($player->toString())->toBeNull();
        expect($player->getSrc())->toBeNull();
    });

    test('can create from valid bandcamp iframe without fallback', function () {
        $iframe = '<iframe style="border: 0; width: 350px; height: 470px;" src="https://bandcamp.com/EmbeddedPlayer/album=123456789/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/transparent=true/" seamless></iframe>';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->toString())->toBe($iframe);
        expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=123456789/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/transparent=true/');
        expect($player->getFallbackUrl())->toBeNull();
        expect($player->getFallbackText())->toBeNull();
    });

    test('can create from valid bandcamp iframe with fallback link', function () {
        $iframe = '<iframe style="border: 0; width: 350px; height: 470px;" src="https://bandcamp.com/EmbeddedPlayer/album=123456789/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/transparent=true/" seamless><a href="https://myalbum.bandcamp.com/album/my-album">My Album by Artist</a></iframe>';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->toString())->toBe($iframe);
        expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=123456789/size=large/bgcol=ffffff/linkcol=0687f5/tracklist=false/transparent=true/');
        expect($player->getFallbackUrl())->toBe('https://myalbum.bandcamp.com/album/my-album');
        expect($player->getFallbackText())->toBe('My Album by Artist');
    });

    test('handles iframe with whitespace', function () {
        $iframe = '  <iframe src="https://bandcamp.com/EmbeddedPlayer/track=987654321"></iframe>  ';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/track=987654321');
    });

    test('handles malformed HTML gracefully (no validation in Portfolio context)', function () {
        $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=123">';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->getSrc())->toBe('https://bandcamp.com/EmbeddedPlayer/album=123');
    });

    test('__toString returns empty string for null player', function () {
        $player = BandcampPlayer::fromString(null);

        expect((string) $player)->toBe('');
    });

    test('__toString returns iframe for valid player', function () {
        $iframe = '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=123"></iframe>';
        $player = BandcampPlayer::fromString($iframe);

        expect((string) $player)->toBe($iframe);
    });

    test('handles non-bandcamp iframe (Portfolio is permissive)', function () {
        $iframe = '<iframe src="https://youtube.com/embed/video123"></iframe>';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->getSrc())->toBe('https://youtube.com/embed/video123');
    });

    test('handles iframe without src attribute', function () {
        $iframe = '<iframe style="border: 0;"></iframe>';

        $player = BandcampPlayer::fromString($iframe);

        expect($player->hasPlayer())->toBeTrue();
        expect($player->getSrc())->toBe('');
        expect($player->toString())->toBe($iframe);
    });
});
