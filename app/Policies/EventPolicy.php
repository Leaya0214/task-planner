<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isEmployee();
    }

    public function view(User $user, Event $event): bool
    {
        return $user->isAdmin() || $event->task->isAssignedTo($user);
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Event $event): bool
    {
        return $user->isAdmin() || $event->user_id === $user->id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->isAdmin();
    }
}
