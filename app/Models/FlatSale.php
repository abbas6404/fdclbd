<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlatSale extends Model
{
    use SoftDeletes;

    protected $table = 'flat_sales';

    protected $fillable = [
        'sale_number',
        'customer_id',
        'flat_id',
        'sales_agent_id',
        'price_per_sqft',
        'total_price',
        'parking_charge',
        'utility_charge',
        'additional_work_charge',
        'other_charge',
        'deduction_amount',
        'refund_amount',
        'net_price',
        'sale_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'price_per_sqft' => 'decimal:2',
        'total_price' => 'decimal:2',
        'parking_charge' => 'decimal:2',
        'utility_charge' => 'decimal:2',
        'additional_work_charge' => 'decimal:2',
        'other_charge' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'net_price' => 'decimal:2',
        'sale_date' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function flat()
    {
        return $this->belongsTo(ProjectFlat::class, 'flat_id');
    }

    public function salesAgent()
    {
        return $this->belongsTo(SalesAgent::class, 'sales_agent_id');
    }

    public function paymentSchedules()
    {
        return $this->hasMany(FlatSalePaymentSchedule::class, 'flat_sale_id');
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
     * Generate next sale number
     */
    public static function generateSaleNumber()
    {
        $lastSale = self::orderBy('id', 'desc')->first();
        $number = $lastSale ? (int) str_replace('SALE-', '', $lastSale->sale_number ?? '0') + 1 : 1;
        return 'SALE-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}

