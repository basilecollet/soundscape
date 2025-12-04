<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use App\Domain\Admin\Exceptions\InvalidBandcampPlayerException;
use DOMDocument;
use DOMXPath;

final readonly class BandcampPlayer
{
    private function __construct(
        private ?string $iframe,
        private ?string $src,
        private ?string $fallbackUrl,
        private ?string $fallbackText,
    ) {}

    public static function fromString(?string $iframe): self
    {
        if ($iframe === null || trim($iframe) === '') {
            return new self(null, null, null, null);
        }

        $iframe = trim($iframe);

        // Validate it's an iframe tag
        if (! str_contains($iframe, '<iframe')) {
            throw InvalidBandcampPlayerException::notIframe();
        }

        // Validate it's from Bandcamp
        if (! str_contains($iframe, 'bandcamp.com')) {
            throw InvalidBandcampPlayerException::notBandcamp();
        }

        // Reasonable length limit for iframe code
        if (mb_strlen($iframe) > 10000) {
            throw InvalidBandcampPlayerException::tooLong(mb_strlen($iframe));
        }

        // Parse and validate the iframe structure
        libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $doc->loadHTML('<?xml encoding="UTF-8">'.$iframe, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        $iframeNodeList = $xpath->query('//iframe');

        if ($iframeNodeList === false) {
            throw InvalidBandcampPlayerException::notIframe();
        }

        $iframeNode = $iframeNodeList->item(0);

        if ($iframeNode === null || ! $iframeNode instanceof \DOMElement) {
            throw InvalidBandcampPlayerException::notIframe();
        }

        // Extract and validate src attribute
        $src = $iframeNode->getAttribute('src');
        if (empty($src)) {
            throw InvalidBandcampPlayerException::notBandcamp();
        }

        // Validate that src is a valid Bandcamp embed URL
        if (! str_starts_with($src, 'https://bandcamp.com/EmbeddedPlayer/')) {
            throw InvalidBandcampPlayerException::notBandcamp();
        }

        // Extract fallback link if present
        $fallbackUrl = null;
        $fallbackText = null;
        $linkNodeList = $xpath->query('//iframe//a');

        if ($linkNodeList !== false) {
            $linkNode = $linkNodeList->item(0);
            if ($linkNode !== null && $linkNode instanceof \DOMElement) {
                $fallbackUrl = $linkNode->getAttribute('href');
                $fallbackText = $linkNode->textContent;
            }
        }

        return new self($iframe, $src, $fallbackUrl, $fallbackText);
    }

    public function toString(): ?string
    {
        return $this->iframe;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function getFallbackUrl(): ?string
    {
        return $this->fallbackUrl;
    }

    public function getFallbackText(): ?string
    {
        return $this->fallbackText;
    }

    public function hasPlayer(): bool
    {
        return $this->iframe !== null;
    }

    public function __toString(): string
    {
        return $this->iframe ?? '';
    }
}
