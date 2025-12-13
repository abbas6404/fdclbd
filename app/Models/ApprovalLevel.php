<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLevel extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'sequence',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sequence' => 'integer',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_approval_levels')
            ->withPivot('is_active')
            ->withTimestamps()
            ->wherePivot('is_active', true);
    }

    public function userApprovalLevels()
    {
        return $this->hasMany(UserApprovalLevel::class);
    }

    public function requisitions()
    {
        return $this->hasMany(Requisition::class, 'current_approval_level_id');
    }

    public function requisitionApprovals()
    {
        return $this->hasMany(RequisitionApproval::class);
    }

    /**
     * Get next approval level in sequence
     */
    public function getNextLevel()
    {
        return self::where('sequence', '>', $this->sequence)
            ->where('is_active', true)
            ->orderBy('sequence', 'asc')
            ->first();
    }

    /**
     * Get previous approval level in sequence
     */
    public function getPreviousLevel()
    {
        return self::where('sequence', '<', $this->sequence)
            ->where('is_active', true)
            ->orderBy('sequence', 'desc')
            ->first();
    }

    /**
     * Get first approval level
     */
    public static function getFirstLevel()
    {
        return self::where('is_active', true)
            ->orderBy('sequence', 'asc')
            ->first();
    }
}
