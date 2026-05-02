<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'departure_date',
        'return_date',
        'quota',
        'booked',
        'price',
        'meeting_point',
        'status',
        'notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Helper: available slots
    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->quota - $this->booked);
    }

    // Helper: is available for booking
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'open'
            && $this->available_slots > 0
            && $this->departure_date->isFuture();
    }

    // Helper: effective price (schedule price or destination price)
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->destination->price;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->effective_price, 0, ',', '.');
    }
}
