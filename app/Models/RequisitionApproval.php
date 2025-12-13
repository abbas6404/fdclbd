<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionApproval extends Model
{
    protected $fillable = [
        'requisition_id',
        'user_id',
        'approval_level_id',
        'approval_date',
        'approval_status',
        'remarks',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
    ];

    // Relationships
    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class);
    }
}
