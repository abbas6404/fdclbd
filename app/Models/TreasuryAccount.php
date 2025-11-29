<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreasuryAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'treasury_accounts';

    protected $fillable = [
        'account_name',
        'account_type',
        'bank_name',
        'account_number',
        'branch_name',
        'opening_balance',
        'current_balance',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'opening_balance' => 'integer', // Stored in paise, supports up to 9,223,372,036,854,775,807 (bigInteger)
        'current_balance' => 'integer', // Stored in paise, supports up to 9,223,372,036,854,775,807 (bigInteger)
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

    // Scopes
    public function scopeCash($query)
    {
        return $query->where('account_type', 'cash');
    }

    public function scopeBank($query)
    {
        return $query->where('account_type', 'bank');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
