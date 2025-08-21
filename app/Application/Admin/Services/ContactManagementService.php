<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Domain\Admin\Repositories\ContactRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactManagementService
{
    public function __construct(
        private readonly ContactRepository $contactRepository
    ) {}

    /**
     * Get unread messages count
     */
    public function getUnreadCount(): int
    {
        return $this->contactRepository->unreadCount();
    }

    /**
     * Get all messages with pagination
     *
     * @return LengthAwarePaginator<int, \App\Models\ContactMessage>
     */
    public function getAllMessages(int $perPage = 15): LengthAwarePaginator
    {
        return $this->contactRepository->paginate($perPage);
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(int $messageId): bool
    {
        return $this->contactRepository->markAsRead($messageId);
    }

    /**
     * Get recent contact messages
     *
     * @return Collection<int, \App\Models\ContactMessage>
     */
    public function getRecentMessages(int $limit = 5): Collection
    {
        return $this->contactRepository->findLatest($limit);
    }
}