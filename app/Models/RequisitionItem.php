<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RequisitionItem extends Model
{
    use SoftDeletes;

    protected $table = 'requisition_items';

    protected $fillable = [
        'requisition_id',
        'head_of_account_id',
        'description',
        'unit',
        'qty',
        'confirmation_status',
        'current_approval_level_id',
        'current_approval_sequence',
        'created_by',
        'updated_by',
        'change_history',
    ];

    protected $casts = [
        'qty' => 'integer',
        'current_approval_sequence' => 'integer',
        'change_history' => 'array',
    ];

    // Relationships
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function headOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'head_of_account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function currentApprovalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class, 'current_approval_level_id');
    }

    public function itemApprovals()
    {
        return $this->hasMany(RequisitionItemApproval::class);
    }

    /**
     * Track changes to the model
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($item) {
            $original = $item->getOriginal();
            $changes = [];
            $trackableFields = ['unit', 'qty', 'description', 'confirmation_status'];

            foreach ($trackableFields as $field) {
                if ($item->isDirty($field)) {
                    $changes[] = [
                        'field' => $field,
                        'old_value' => $original[$field] ?? null,
                        'new_value' => $item->$field,
                        'changed_by' => Auth::id(),
                        'changed_at' => now()->toDateTimeString(),
                    ];
                }
            }

            if (!empty($changes)) {
                $history = $item->change_history ?? [];
                $history = array_merge($history, $changes);
                $item->change_history = $history;
            }
        });
    }

    /**
     * Get change history as formatted array
     */
    public function getChangeHistory()
    {
        return $this->change_history ?? [];
    }
}
