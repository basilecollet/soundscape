<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Exceptions;

use Exception;

final class InvalidProjectDescriptionException extends Exception
{
    public static function empty(): self
    {
        return new self('Project description cannot be empty');
    }
}
