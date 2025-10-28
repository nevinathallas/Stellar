<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class Category extends Model
{
    protected $fillable = ['name'];

    /**
     * Get units in this category
     */
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_categories');
    }
}
