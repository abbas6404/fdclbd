<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebitVoucherItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'debit_voucher_id',
        'head_of_account_id',
        'amount',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    // Relationships
    public function debitVoucher()
    {
        return $this->belongsTo(DebitVoucher::class);
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
}
