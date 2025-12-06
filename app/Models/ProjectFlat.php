<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectFlat extends Model
{
    use SoftDeletes;

    protected $table = 'flats';

    protected $fillable = [
        'project_id',
        'flat_number',
        'flat_type',
        'floor_number',
        'flat_size',
        'status',
        'created_by',
        'updated_by'
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'flat_id');
    }

    public function flatSales()
    {
        return $this->hasMany(FlatSale::class, 'flat_id');
    }

    public function paymentSchedules()
    {
        return $this->hasMany(FlatSalePaymentSchedule::class, 'flat_id');
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