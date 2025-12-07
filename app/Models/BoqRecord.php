<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class BoqRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'boq_records';

    protected $fillable = [
        'project_id',
        'head_of_account_id',
        'planned_quantity',
        'used_quantity',
        'unit_rate',
        'change_history',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:2',
        'used_quantity' => 'decimal:2',
        'unit_rate' => 'decimal:2',
        'change_history' => 'array',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
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

    /**
     * Calculate total planned amount
     */
    public function getPlannedAmountAttribute()
    {
        return $this->planned_quantity * $this->unit_rate;
    }

    /**
     * Calculate total used amount
     */
    public function getUsedAmountAttribute()
    {
        return $this->used_quantity * $this->unit_rate;
    }

    /**
     * Calculate remaining quantity
     */
    public function getRemainingQuantityAttribute()
    {
        return $this->planned_quantity - $this->used_quantity;
    }

    /**
     * Track changes to the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
                
                // Track changes
                $changes = $model->getDirty();
                $original = $model->getOriginal();
                
                if (!empty($changes)) {
                    $history = $model->change_history ?? [];
                    $history[] = [
                        'date' => now()->toDateTimeString(),
                        'user_id' => Auth::id(),
                        'user_name' => Auth::user()->name ?? 'Unknown',
                        'changes' => collect($changes)->map(function ($value, $key) use ($original) {
                            return [
                                'field' => $key,
                                'old_value' => $original[$key] ?? null,
                                'new_value' => $value,
                            ];
                        })->values()->toArray(),
                    ];
                    $model->change_history = $history;
                }
            }
        });
    }
}

