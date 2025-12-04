<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Entities\ValueObjects;

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

        // Parse the iframe to extract safe data
        libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $doc->loadHTML('<?xml encoding="UTF-8">'.$iframe, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        $iframeNodeList = $xpath->query('//iframe');

        $src = null;
        $fallbackUrl = null;
        $fallbackText = null;

        if ($iframeNodeList !== false) {
            $iframeNode = $iframeNodeList->item(0);

            if ($iframeNode !== null && $iframeNode instanceof \DOMElement) {
                $src = $iframeNode->getAttribute('src');

                // Extract fallback link if present
                $linkNodeList = $xpath->query('//iframe//a');
                if ($linkNodeList !== false) {
                    $linkNode = $linkNodeList->item(0);
                    if ($linkNode !== null && $linkNode instanceof \DOMElement) {
                        $fallbackUrl = $linkNode->getAttribute('href');
                        $fallbackText = $linkNode->textContent;
                    }
                }
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
