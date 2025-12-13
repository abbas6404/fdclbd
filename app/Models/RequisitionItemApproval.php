<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionItemApproval extends Model
{
    protected $fillable = [
        'requisition_item_id',
        'user_id',
        'approval_level_id',
        'approval_date',
        'approval_status',
        'remarks',
    ];

    protected $casts = [
        'approval_date' => 'datetime',
    ];

    public function requisitionItem()
    {
        return $this->belongsTo(RequisitionItem::class);
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

