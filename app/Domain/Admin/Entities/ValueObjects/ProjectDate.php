<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entities\ValueObjects;

use Carbon\Carbon;

final readonly class ProjectDate
{
    private function __construct(
        private Carbon $date,
    ) {}

    public static function fromCarbon(Carbon $date): self
    {
        return new self($date);
    }

    public static function fromString(string $date): self
    {
        return new self(Carbon::parse($date));
    }

    public function toCarbon(): Carbon
    {
        return $this->date;
    }

    public function format(string $format): string
    {
        return $this->date->format($format);
    }
}