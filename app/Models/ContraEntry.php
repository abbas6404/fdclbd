<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContraEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(ContraEntryItem::class);
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
     * Generate next entry number
     */
    public static function generateEntryNumber()
    {
        return \App\Helpers\VoucherHelper::generateContraEntryNumber();
    }
}
