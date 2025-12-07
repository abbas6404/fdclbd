<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_order_number',
        'purchase_order_date',
        'required_date',
        'total_amount',
        'remark',
        'requisition_id',
        'project_id',
        'employee_id',
        'supplier_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'purchase_order_date' => 'date',
        'required_date' => 'date',
        'total_amount' => 'integer',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
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
     * Generate next purchase order number
     */
    public static function generatePurchaseOrderNumber()
    {
        $lastPO = self::orderBy('id', 'desc')->first();
        $number = $lastPO ? (int) str_replace('PO-', '', $lastPO->purchase_order_number ?? '0') + 1 : 1;
        return 'PO-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate total amount from items
     */
    public function calculateTotalAmount()
    {
        $total = $this->items()->sum('amount');
        $this->total_amount = $total;
        $this->save();
        return $this->total_amount;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}

