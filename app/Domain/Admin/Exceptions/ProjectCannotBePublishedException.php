<?php

declare(strict_types=1);

namespace App\Domain\Admin\Exceptions;

use App\Domain\Admin\Entities\Enums\ProjectStatus;

final class ProjectCannotBePublishedException extends \DomainException
{
    private ?ProjectStatus $status = null;

    public static function invalidStatus(ProjectStatus $status): self
    {
        $instance = new self(
            sprintf('Technical: Cannot publish project with status "%s". Only draft projects can be published.', $status->value)
        );
        $instance->status = $status;

        return $instance;
    }

    public static function missingDescription(): self
    {
        return new self('Technical: Cannot publish project without description');
    }

    public function getStatus(): ?ProjectStatus
    {
        return $this->status;
    }

    public function hasMissingDescription(): bool
    {
        return $this->status === null;
    }
}
