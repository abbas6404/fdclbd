<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlatSalePaymentSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'flat_sale_payment_schedules';

    protected $fillable = [
        'flat_id',
        'term_name',
        'receivable_amount',
        'received_amount',
        'due_date',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'receivable_amount' => 'integer',
        'received_amount' => 'integer',
        'due_date' => 'datetime',
    ];

    // Relationships
    public function flat()
    {
        return $this->belongsTo(ProjectFlat::class, 'flat_id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(PaymentInvoiceItem::class, 'payment_schedule_id');
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

