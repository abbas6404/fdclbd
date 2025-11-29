<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransactionDebit extends Model
{
    use SoftDeletes;

    protected $table = 'account_transaction_debits';

    protected $fillable = [
        'transaction_id',
        'chart_of_account_id',
        'amount',
        'description',
        'reference',
        'sequence',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(AccountTransaction::class, 'transaction_id');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'chart_of_account_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
