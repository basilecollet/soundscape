<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\Enums\ProjectStatus;

final class ProjectCannotBeArchivedException extends \DomainException
{
    public static function invalidStatus(ProjectStatus $status): self
    {
        return new self(
            sprintf('Cannot archive project with status "%s". Only published projects can be archived.', $status->value)
        );
    }
}
