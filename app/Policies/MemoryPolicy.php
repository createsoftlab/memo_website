<?php

namespace App\Policies;

use App\Models\Memory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any memories.
     */
    public function viewAny(User $user): bool
    {
        return $user->canAccessModule('memories');
    }

    /**
     * Determine whether the user can view the specific memory.
     */
    public function view(User $user, Memory $memory): bool
    {
        return $user->canAccessModule('memories');
    }

    /**
     * Determine whether the user can create memories.
     * Super Admin and Admin CANNOT create memories.
     * Regular users require an active package and available quota.
     */
    public function create(User $user): bool
    {
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return false;
        }

        return $user->canCreateMemory();
    }

    /**
     * Determine whether the user can update the memory.
     */
    public function update(User $user, Memory $memory): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->hasPermission('memories', 'edit')) {
            return true;
        }

        // Memory Creator can update memories they own
        if ($user->isMemoryUser() && $memory->profiles()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the memory.
     */
    public function delete(User $user, Memory $memory): bool
    {
        return $user->canDelete();
    }
}
