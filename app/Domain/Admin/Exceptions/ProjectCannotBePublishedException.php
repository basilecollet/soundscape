<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\Enums\ProjectStatus;

final class ProjectCannotBePublishedException extends \DomainException
{
    public static function invalidStatus(ProjectStatus $status): self
    {
        return new self(
            sprintf('Cannot publish project with status "%s". Only draft projects can be published.', $status->value)
        );
    }

    public static function missingDescription(): self
    {
        return new self('Cannot publish project without description');
    }
}
