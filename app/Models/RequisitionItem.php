<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisitionItem extends Model
{
    use SoftDeletes;

    protected $table = 'requisition_items';

    protected $fillable = [
        'requisition_id',
        'chart_of_account_id',
        'description',
        'qty',
        'rate',
        'amount',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate amount when qty or rate changes
        static::saving(function ($item) {
            if ($item->qty && $item->rate) {
                $item->amount = $item->qty * $item->rate;
            }
        });
    }

    // Relationships
    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(HeadOfAccount::class, 'chart_of_account_id');
    }

    /**
     * Calculate amount based on qty and rate
     */
    public function calculateAmount()
    {
        $this->amount = $this->qty * $this->rate;
        return $this->amount;
    }
}
