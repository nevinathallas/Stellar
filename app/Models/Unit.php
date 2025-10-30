<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['code', 'name', 'image_url', 'price_per_day', 'status'];

    /**
     * Get categories for this unit
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'unit_categories');
    }

    /**
     * Get rentals for this unit
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Get current active rental
     */
    public function currentRental()
    {
        return $this->hasOne(Rental::class)->where('status', 'ongoing')->latest();
    }

    /**
     * Check if unit is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if unit is rented
     */
    public function isRented(): bool
    {
        return $this->status === 'rented';
    }

    /**
     * Scope to get only available units
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope to search by name
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }
}
