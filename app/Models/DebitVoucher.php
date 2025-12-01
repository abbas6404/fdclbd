<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DebitVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'voucher_number',
        'voucher_date',
        'remarks',
        'total_amount',
        'created_by',
        'updated_by',
        'change_history',
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'total_amount' => 'integer',
        'change_history' => 'array',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(DebitVoucherItem::class);
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
     * Generate next voucher number
     */
    public static function generateVoucherNumber()
    {
        return \App\Helpers\VoucherHelper::generateDebitVoucherNumber();
    }

    /**
     * Track changes to the model
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($voucher) {
            $original = $voucher->getOriginal();
            $changes = [];
            $trackableFields = ['voucher_date', 'remarks', 'total_amount'];

            foreach ($trackableFields as $field) {
                if ($voucher->isDirty($field)) {
                    $changes[] = [
                        'field' => $field,
                        'old_value' => $original[$field] ?? null,
                        'new_value' => $voucher->$field,
                        'changed_by' => Auth::id(),
                        'changed_at' => now()->toDateTimeString(),
                    ];
                }
            }

            if (!empty($changes)) {
                $history = $voucher->change_history ?? [];
                $history = array_merge($history, $changes);
                $voucher->change_history = $history;
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
