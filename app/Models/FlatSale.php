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
        'sale_date',
        'nominee_name',
        'nominee_nid',
        'nominee_phone',
        'nominee_relationship',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
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

