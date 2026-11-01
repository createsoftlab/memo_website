<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'country',
        'state',
        'role',
        'package_id',
        'package_expires_at',
        'status',
        'permissions',
        'avatar',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'permissions' => 'array',
        'package_expires_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'package_expires_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    /**
     * Determine if the user is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Determine if the user is an Admin or Super Admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    /**
     * Determine if the user is an Employee.
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Determine if the user is a standard registered user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Determine if the user is a Memory Created User or standard user.
     */
    public function isMemoryUser(): bool
    {
        return in_array($this->role, ['memory_user', 'user'], true);
    }

    /**
     * Determine if the user is a General User.
     */
    public function isGeneralUser(): bool
    {
        return in_array($this->role, ['general_user', 'user'], true);
    }

    /**
     * Determine if user has access to a specific module.
     * Super Admin always has full access to all modules.
     */
    public function canAccessModule(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Standard memorial / memory users have access to dashboard, memories, and events modules
        if ($this->isMemoryUser() && in_array($module, ['dashboard', 'memories', 'events', 'profile_pages', 'settings'], true)) {
            return true;
        }

        $permissions = $this->permissions ?? [];
        if (!is_array($permissions)) {
            return false;
        }

        // Flat array of modules: ['dashboard', 'memories', ...]
        if (in_array($module, $permissions, true)) {
            return true;
        }

        // Associative format: ['memories' => ['view' => 1]] or ['memories' => 1]
        $modulePermissions = $permissions[$module] ?? [];
        if (is_array($modulePermissions)) {
            return !empty(array_filter($modulePermissions));
        }

        return (bool) $modulePermissions;
    }

    /**
     * Determine if user has module-level permission (alias for canAccessModule).
     */
    public function hasModulePermission(string $module): bool
    {
        return $this->canAccessModule($module);
    }

    /**
     * Determine if user has delete authority.
     * Super Admin has full delete rights; Admins and Employees can NEVER delete any data.
     */
    public function canDelete(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Determine if user has permission for a specific module and action.
     */
    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Hard restriction: Admins and Employees can never delete records
        if ($action === 'delete') {
            return false;
        }

        // Memory User default module permissions
        if ($this->isMemoryUser()) {
            // Memory users can view, create, and edit their memories and events
            if (in_array($module, ['memories', 'events'], true)) {
                return in_array($action, ['view', 'create', 'store', 'edit', 'update'], true);
            }
            if (in_array($module, ['dashboard', 'profile_pages', 'settings'], true) && $action === 'view') {
                return true;
            }
        }

        $permissions = $this->permissions ?? [];
        if (!is_array($permissions)) {
            return false;
        }

        // Associative format: ['events' => ['view' => 1, 'approve' => 1]]
        if (isset($permissions[$module]) && is_array($permissions[$module])) {
            if (!empty($permissions[$module][$action])) {
                return true;
            }
            // If checking 'view' action, possessing any active module permission allows viewing
            if ($action === 'view') {
                return !empty(array_filter($permissions[$module]));
            }
            return false;
        }

        // Flat array format fallback: ['dashboard', 'events']
        return in_array($module, $permissions, true);
    }

    /**
     * Determine if the model has the given direct permission.
     * Overridden to prevent collision between the users.permissions JSON column and Spatie's relationship.
     */
    public function hasDirectPermission($permission): bool
    {
        try {
            $permission = $this->filterPermission($permission);

            $rawPermissions = $this->getAttribute('permissions');
            if (is_array($rawPermissions) && in_array($permission->name, $rawPermissions, true)) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Get the package relation.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the profiles (memories) created by the user.
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Get the memories created by the user.
     */
    public function memories(): HasMany
    {
        return $this->hasMany(Memory::class);
    }

    /**
     * Evaluate whether user has a purchased, non-expired active package.
     */
    public function getActivePackage(): ?Package
    {
        // 1. Direct package relation on user
        if ($this->package_id && $this->package && $this->package->is_active) {
            if ($this->package_expires_at === null || $this->package_expires_at->isFuture()) {
                return $this->package;
            }
        }

        // 2. Stripe Cashier subscription
        if (method_exists($this, 'subscribed') && $this->subscribed()) {
            $sub = $this->subscription();
            if ($sub && $sub->valid()) {
                $pkg = Package::where('stripe_price_id', $sub->stripe_price)->where('is_active', true)->first();
                if ($pkg) {
                    return $pkg;
                }
            }
        }

        return null;
    }

    /**
     * Checks whether the user has a valid, non-expired purchased package.
     */
    public function hasActivePackage(): bool
    {
        return $this->getActivePackage() !== null;
    }

    /**
     * Returns the remaining duration of the active package in days for dashboard display.
     */
    public function remainingDays(): int
    {
        $package = $this->getActivePackage();
        if (!$package) {
            return 0;
        }

        if ($this->package_expires_at) {
            $diff = (int) ceil(now()->diffInSeconds($this->package_expires_at, false) / 86400);
            return max(0, $diff);
        }

        if (method_exists($this, 'subscribed') && $this->subscribed()) {
            $sub = $this->subscription();
            if ($sub && $sub->ends_at) {
                $diff = (int) now()->diffInDays($sub->ends_at, false);
                return max(0, $diff);
            }
        }

        if ($package->duration_unit === 'unlimited' || $package->billing_type === 'lifetime') {
            return 9999;
        }

        return (int) ($package->duration_value ?? 30);
    }

    /**
     * Return remaining memory creation quota.
     */
    public function remainingMemoriesQuota(): int
    {
        $package = $this->getActivePackage();
        if (!$package) {
            return 0;
        }

        $limit = $package->memories_limit ?? 1;
        if ($limit === -1) {
            return 9999; // Unlimited
        }

        $used = max($this->profiles()->count(), $this->memories()->count());
        return max(0, $limit - $used);
    }

    /**
     * Return remaining event creation quota.
     */
    public function remainingEventsQuota(): int
    {
        $package = $this->getActivePackage();
        if (!$package) {
            return 0;
        }

        $limit = $package->events_limit ?? 1;
        if ($limit === -1) {
            return 9999; // Unlimited
        }

        $used = $this->events()->count();
        return max(0, $limit - $used);
    }

    /**
     * Validates that an active package exists and the memory items limit has not been reached.
     * Super Admin and Admin CAN NEVER create memories.
     */
    public function canCreateMemory(): bool
    {
        if (in_array($this->role, ['super_admin', 'admin'])) {
            return false;
        }

        if ($this->package_id === null || !$this->hasActivePackage()) {
            return false;
        }

        $package = $this->getActivePackage();
        $limit = $package->memories_limit ?? 1;

        if ($limit === -1) {
            return true;
        }

        $used = max($this->profiles()->count(), $this->memories()->count());
        return $used < $limit;
    }

    /**
     * Validates that an active package exists and the event items limit has not been reached.
     * Super Admin and Admin CAN NEVER create events.
     */
    public function canCreateEvent(): bool
    {
        if (in_array($this->role, ['super_admin', 'admin'])) {
            return false;
        }

        if ($this->package_id === null || !$this->hasActivePackage()) {
            return false;
        }

        $package = $this->getActivePackage();
        $limit = $package->events_limit ?? 1;

        if ($limit === -1) {
            return true;
        }

        return $this->events()->count() < $limit;
    }

    /**
     * Get formatted avatar URL with ui-avatars fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset($this->avatar);
        }

        $name = urlencode($this->name ?: 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=D4AF37&color=ffffff&bold=true";
    }

    /**
     * Human readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'employee' => 'Employee',
            'memory_user' => 'Memory Created User',
            'general_user' => 'General User',
            default => ucfirst(str_replace('_', ' ', $this->role ?? 'User')),
        };
    }

    /**
     * Get the events created by the user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
