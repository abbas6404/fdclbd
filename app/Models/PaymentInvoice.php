<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PaymentCheque;

class PaymentInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'payment_invoices';

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'total_amount',
        'payment_method',
        'remark',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invoice_number' => 'integer',
        'total_amount' => 'integer',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(PaymentInvoiceItem::class, 'invoice_id');
    }

    public function invoiceCheques()
    {
        return $this->hasMany(PaymentInvoiceCheque::class, 'payment_invoice_id');
    }

    public function cheques()
    {
        return $this->hasManyThrough(PaymentCheque::class, PaymentInvoiceCheque::class, 'payment_invoice_id', 'id', 'id', 'cheque_id');
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
     * Generate next invoice number
     */
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('invoice_number', 'desc')->first();
        return $lastInvoice ? $lastInvoice->invoice_number + 1 : 1;
    }
}

