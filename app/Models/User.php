<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,Billable ,HasRoles;


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
     * Determine if the user is a Memory Created User.
     */
    public function isMemoryUser(): bool
    {
        return $this->role === 'memory_user';
    }

    /**
     * Determine if the user is a General User.
     */
    public function isGeneralUser(): bool
    {
        return $this->role === 'general_user';
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
