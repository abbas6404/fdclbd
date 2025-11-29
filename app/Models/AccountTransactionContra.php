<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransactionContra extends Model
{
    use SoftDeletes;

    protected $table = 'account_transaction_contra';

    protected $fillable = [
        'transaction_id',
        'from_account_id',
        'to_account_id',
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

    public function fromAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'to_account_id');
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
