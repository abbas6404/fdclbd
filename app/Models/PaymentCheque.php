<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PaymentCheque extends Model
{
    use SoftDeletes;

    protected $table = 'payment_cheques';

    protected $fillable = [
        'cheque_number',
        'bank_name',
        'cheque_amount',
        'cheque_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'cheque_amount' => 'integer',
        'cheque_date' => 'datetime',
    ];

    // Relationships
    public function invoiceCheques()
    {
        return $this->hasMany(PaymentInvoiceCheque::class, 'cheque_id');
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

