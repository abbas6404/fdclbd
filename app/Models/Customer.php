<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'father_or_husband_name',
        'phone',
        'email',
        'nid_or_passport_number',
        'address',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function flatSales()
    {
        return $this->hasMany(FlatSale::class, 'customer_id');
    }
}
