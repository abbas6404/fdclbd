<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'requisition_number',
        'requisition_date',
        'required_date',
        'total_amount',
        'status',
        'remark',
        'employee_id',
        'project_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'requisition_date' => 'date',
        'required_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(RequisitionItem::class, 'requisition_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
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
     * Generate next requisition number
     */
    public static function generateRequisitionNumber()
    {
        $lastRequisition = self::orderBy('id', 'desc')->first();
        $number = $lastRequisition ? (int) str_replace('REQ-', '', $lastRequisition->requisition_number ?? '0') + 1 : 1;
        return 'REQ-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate total amount from items
     */
    public function calculateTotalAmount()
    {
        $this->total_amount = $this->items()->sum('amount');
        $this->save();
        return $this->total_amount;
    }
}
