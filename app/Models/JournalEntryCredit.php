<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class JournalEntryCredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'journal_entry_id',
        'head_of_account_id',
        'amount',
        'description',
        'created_by',
        'updated_by',
        'change_history',
    ];

    protected $casts = [
        'amount' => 'integer',
        'change_history' => 'array',
    ];

    // Relationships
    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function headOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class);
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
            $trackableFields = ['head_of_account_id', 'amount', 'description'];

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
