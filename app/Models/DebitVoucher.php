<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DebitVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'voucher_number',
        'voucher_date',
        'remarks',
        'total_amount',
        'treasury_account_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'total_amount' => 'integer',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(DebitVoucherItem::class);
    }

    public function treasuryAccount()
    {
        return $this->belongsTo(TreasuryAccount::class);
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
}
