<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Repositories\ContactRepository;
use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactDatabaseRepository implements ContactRepository
{
    public function count(): int
    {
        return ContactMessage::count();
    }

    public function unreadCount(): int
    {
        return ContactMessage::whereNull('read_at')->count();
    }

    public function findLatest(int $limit): Collection
    {
        return ContactMessage::latest()->take($limit)->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): ContactMessage
    {
        return ContactMessage::create($data);
    }

    public function findById(int $id): ?ContactMessage
    {
        return ContactMessage::find($id);
    }

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return ContactMessage::latest()->paginate($perPage);
    }

    public function markAsRead(int $id): bool
    {
        $message = ContactMessage::find($id);

        if ($message) {
            $message->markAsRead();

            return true;
        }

        return false;
    }
}
