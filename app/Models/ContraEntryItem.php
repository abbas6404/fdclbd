<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContraEntryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contra_entry_id',
        'treasury_account_id',
        'entry_type',
        'amount',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    // Relationships
    public function contraEntry()
    {
        return $this->belongsTo(ContraEntry::class);
    }

    public function treasuryAccount()
    {
        return $this->belongsTo(TreasuryAccount::class);
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
