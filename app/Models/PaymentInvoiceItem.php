<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInvoiceItem extends Model
{
    use SoftDeletes;

    protected $table = 'payment_invoice_items';

    protected $fillable = [
        'invoice_id',
        'payment_schedule_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(PaymentInvoice::class, 'invoice_id');
    }

    public function paymentSchedule()
    {
        return $this->belongsTo(FlatSalePaymentSchedule::class, 'payment_schedule_id');
    }
}

