<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('events');
    }

    /**
     * Determine whether the user can view the specific event.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->canAccessModule('events');
    }

    /**
     * Determine whether the user can create events.
     * Super Admin and Admin CANNOT create events.
     * Regular users require an active package and available quota.
     */
    public function create(User $user): bool
    {
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return false;
        }

        return $user->canCreateEvent();
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->hasPermission('events', 'edit')) {
            return true;
        }

        // Author can update their own events
        return $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->canDelete();
    }

    /**
     * Determine whether the user can approve or reject the event.
     */
    public function approve(User $user): bool
    {
        return $user->isSuperAdmin()
            || $user->hasPermission('events', 'approve');
    }
}
