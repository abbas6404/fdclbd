<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'document_name',
        'file_path',
        'file_size',
        'display_order',
        'project_id',
        'flat_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'display_order' => 'integer',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function flat()
    {
        return $this->belongsTo(ProjectFlat::class, 'flat_id');
    }
}
