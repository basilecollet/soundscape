<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\Enums\ProjectStatus;

final class ProjectCannotBeArchivedException extends \DomainException
{
    private ProjectStatus $status;

    public static function invalidStatus(ProjectStatus $status): self
    {
        $instance = new self(
            sprintf('Technical: Cannot archive project with status "%s". Only published projects can be archived.', $status->value)
        );
        $instance->status = $status;

        return $instance;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }
}
