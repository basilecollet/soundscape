<?php

declare(strict_types=1);

namespace App\Domain\Admin\Repositories;

use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepository
{
    public function count(): int;

    public function unreadCount(): int;

    /**
     * @return Collection<int, ContactMessage>
     */
    public function findLatest(int $limit): Collection;

    public function create(array $data): ContactMessage;

    public function findById(int $id): ?ContactMessage;

    /**
     * @return LengthAwarePaginator<int, ContactMessage>
     */
    public function paginate(int $perPage): LengthAwarePaginator;
}