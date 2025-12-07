<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_order_id',
        'head_of_account_id',
        'description',
        'unit',
        'qty',
        'amount',
        'receiving_confirmation',
    ];

    protected $casts = [
        'qty' => 'integer',
        'amount' => 'integer',
    ];

    // Relationships
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function headOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'head_of_account_id');
    }
}

