<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_name',
        'address',
        'description',
        'facing',
        'building_height',
        'land_area',
        'total_floors',
        'project_launching_date',
        'project_hand_over_date',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'project_launching_date' => 'date',
        'project_hand_over_date' => 'date',
    ];

    // Relationships
    public function flats()
    {
        return $this->hasMany(ProjectFlat::class);
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
