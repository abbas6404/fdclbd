<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'transaction_type',
        'reference_number',
        'description',
        'remarks',
        'total_amount',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function debits()
    {
        return $this->hasMany(AccountTransactionDebit::class, 'transaction_id');
    }

    public function credits()
    {
        return $this->hasMany(AccountTransactionCredit::class, 'transaction_id');
    }

    public function contraEntries()
    {
        return $this->hasMany(AccountTransactionContra::class, 'transaction_id');
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
     * Generate next transaction number based on type
     */
    public static function generateTransactionNumber($type = 'debit_entry')
    {
        $prefixes = [
            'debit_entry' => 'DV',
            'credit_entry' => 'CV',
            'journal_entry' => 'JV',
            'contra_entry' => 'CT',
        ];

        $prefix = $prefixes[$type] ?? 'VOU';
        $lastTransaction = self::where('transaction_type', $type)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastTransaction ? (int) str_replace($prefix . '-', '', $lastTransaction->transaction_number ?? '0') + 1 : 1;
        return $prefix . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate total amount from debits, credits, and contra entries
     */
    public function calculateTotalAmount()
    {
        if ($this->transaction_type === 'contra_entry') {
            // For contra entries, use contra table
            $this->total_amount = $this->contraEntries()->sum('amount');
        } else {
            // For other entries, use debits and credits
            $debitTotal = $this->debits()->sum('amount');
            $creditTotal = $this->credits()->sum('amount');
            $this->total_amount = max($debitTotal, $creditTotal);
        }
        $this->save();
        return $this->total_amount;
    }
}
