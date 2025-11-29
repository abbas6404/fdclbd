<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class PaymentInvoiceCheque extends Model
{
    use SoftDeletes;

    protected $table = 'payment_invoice_cheques';

    protected $fillable = [
        'cheque_id',
        'payment_invoice_id',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function cheque()
    {
        return $this->belongsTo(PaymentCheque::class, 'cheque_id');
    }

    public function invoice()
    {
        return $this->belongsTo(PaymentInvoice::class, 'payment_invoice_id');
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

