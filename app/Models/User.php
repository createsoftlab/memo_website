<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_BANNED = 'banned';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'country',
        'state',
        'registration_ip',
        'status',
        'is_memory_creator',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_memory_creator' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isBanned(): bool
    {
        return $this->status === self::STATUS_BANNED;
    }

    /*
    |--------------------------------------------------------------------------
    | Role & Profile Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin') || $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(['Super Admin', 'Admin']) || in_array($this->role, ['super_admin', 'admin'], true);
    }

    public function isEmployee(): bool
    {
        return $this->hasRole('Employee') || $this->role === 'employee';
    }

    /**
     * Easily distinguish between standard registered users and memory-listed users.
     */
    public function isMemoryCreator(): bool
    {
        return (bool) $this->is_memory_creator
            || $this->hasRole('Memory Created User')
            || ($this->relationLoaded('events') ? $this->events->isNotEmpty() : $this->events()->exists());
    }

    /*
    |--------------------------------------------------------------------------
    | Model Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeSuspended(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    public function scopeBanned(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_BANNED);
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopeMemoryCreators(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('is_memory_creator', true)
              ->orWhereHas('roles', fn (Builder $r) => $r->where('name', 'Memory Created User'))
              ->orWhereHas('events');
        });
    }

    public function scopeGeneralUsers(Builder $query): Builder
    {
        return $query->where('is_memory_creator', false)
            ->whereDoesntHave('events')
            ->where(function (Builder $q) {
                $q->whereHas('roles', fn (Builder $r) => $r->where('name', 'General User'))
                  ->orWhereDoesntHave('roles');
            });
    }

    public function scopeStaff(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereHas('roles', fn (Builder $r) => $r->whereIn('name', ['Super Admin', 'Admin', 'Employee']))
              ->orWhereIn('role', ['super_admin', 'admin', 'employee']);
        });
    }

    /**
     * Filter scope for search, country, state, status, and role.
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $search = '%' . trim($filters['search']) . '%';
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('registration_ip', 'like', $search);
            });
        }

        if (!empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        if (!empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles', fn (Builder $r) => $r->where('name', $filters['role']));
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the events created by the user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
