<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CreditVoucherItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'credit_voucher_id',
        'head_of_account_id',
        'treasury_account_id',
        'purchase_order_id',
        'project_id',
        'amount',
        'description',
        'bank_name',
        'check_number',
        'created_by',
        'updated_by',
        'change_history',
    ];

    protected $casts = [
        'amount' => 'integer',
        'change_history' => 'array',
    ];

    // Relationships
    public function creditVoucher()
    {
        return $this->belongsTo(CreditVoucher::class);
    }

    public function headOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class);
    }

    public function treasuryAccount()
    {
        return $this->belongsTo(TreasuryAccount::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
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
     * Track changes to the model
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($item) {
            $original = $item->getOriginal();
            $changes = [];
            $trackableFields = ['head_of_account_id', 'treasury_account_id', 'purchase_order_id', 'project_id', 'amount', 'description', 'bank_name', 'check_number'];

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
