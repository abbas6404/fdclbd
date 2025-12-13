<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'profile_photo_path',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Check if the user account is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user account is suspended.
     *
     * @return bool
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Get the user's last login time.
     *
     * @return \Carbon\Carbon|null
     */
    public function getLastLoginAt()
    {
        return $this->last_login_at;
    }

    /**
     * Update the user's last login time.
     *
     * @return void
     */
    public function updateLastLoginAt()
    {
        $this->update(['last_login_at' => now()]);
    }

    // Approval Level Relationships
    public function approvalLevels()
    {
        return $this->belongsToMany(ApprovalLevel::class, 'user_approval_levels')
            ->withPivot('is_active')
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }

    public function userApprovalLevels()
    {
        return $this->hasMany(UserApprovalLevel::class);
    }

    /**
     * Get user's current active approval level
     */
    public function getCurrentApprovalLevel()
    {
        return $this->approvalLevels()->first();
    }

    /**
     * Check if user has approval level
     */
    public function hasApprovalLevel($levelId)
    {
        return $this->approvalLevels()->where('approval_levels.id', $levelId)->exists();
    }

    /**
     * Get projects created by this user
     */
    public function createdProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    /**
     * Get projects updated by this user
     */
    public function updatedProjects()
    {
        return $this->hasMany(Project::class, 'updated_by');
    }
}
