<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSection extends Model
{
    protected $fillable = [
        'project_id',
        'type',
        'content',
        'meta',
        'position',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function sections()
    {
        return $this->hasMany(ProjectSection::class)
            ->orderBy('position');
    }

    protected $casts = [

        'meta' => 'array',

    ];
}