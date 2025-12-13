<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApprovalLevel extends Model
{
    protected $fillable = [
        'user_id',
        'approval_level_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class);
    }
}
