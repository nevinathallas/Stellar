<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    protected $fillable = [
        'user_id',
        'unit_id',
        'start_date',
        'end_date',
        'returned_at',
        'duration_days',
        'fine',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'returned_at' => 'datetime',
        'fine' => 'decimal:2'
    ];

    /**
     * Accessor untuk due_date (alias dari end_date)
     * Agar view tetap bisa pakai $rental->due_date
     */
    public function getDueDateAttribute()
    {
        return $this->end_date;
    }

    /**
     * Accessor untuk rental_date (alias dari start_date)
     */
    public function getRentalDateAttribute()
    {
        return $this->start_date;
    }

    /**
     * Accessor untuk return_date (alias dari returned_at)
     */
    public function getReturnDateAttribute()
    {
        return $this->returned_at;
    }

    /**
     * Get the user (member) who rents
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the unit being rented
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Check if rental is ongoing
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    /**
     * Check if rental is returned
     */
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Check if rental is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    /**
     * Calculate if rental is late
     */
    public function calculateDaysLate(): int
    {
        if ($this->returned_at) {
            $endDate = Carbon::parse($this->end_date);
            $returnedDate = Carbon::parse($this->returned_at);
            
            if ($returnedDate->greaterThan($endDate)) {
                return $returnedDate->diffInDays($endDate);
            }
        } else {
            // Still ongoing, check if overdue
            $endDate = Carbon::parse($this->end_date);
            $today = Carbon::today();
            
            if ($today->greaterThan($endDate)) {
                return $today->diffInDays($endDate);
            }
        }
        
        return 0;
    }

    /**
     * Calculate fine amount (Rp 100,000 per day)
     */
    public function calculateFine(): float
    {
        $daysLate = $this->calculateDaysLate();
        return $daysLate * 100000; // Rp 100.000 per hari
    }

    /**
     * Scope to get ongoing rentals
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope to get overdue rentals
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope to get returned rentals
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
}
