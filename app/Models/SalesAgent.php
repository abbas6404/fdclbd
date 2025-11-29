<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesAgent extends Model
{
    use SoftDeletes;

    protected $table = 'sales_agents';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'nid_or_passport_number',
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
}
