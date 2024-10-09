<?php

namespace Itpi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'menus', 'feature_id', 'project_id');
    }
}
