<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProjectSection;

class Project extends Model
{
    protected $fillable = [
        'title',
        'short_description',
        'image',
        'github_link',
        'technologies',
        'project_date'
    ];

    public function sections()
    {
        return $this->hasMany(ProjectSection::class)
            ->orderBy('position');
    }
}