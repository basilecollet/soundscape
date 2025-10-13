<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

final class InvalidProjectDescriptionException extends \DomainException
{
    public static function empty(): self
    {
        return new self('Project description cannot be empty');
    }
}
