<?php

namespace Itpi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['url', 'key', 'encryption'];

    public function users()
    {
        return $this->hasMany(User::class, 'project_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Feature::class, 'menus', 'project_id', 'feature_id')->withPivot('flag_active');
    }

    /**
     * Get all of the companies for the Project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(ProjectCompany::class, 'project_id', 'id');
    }
}
