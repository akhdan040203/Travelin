<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'schedule_id',
        'participants',
        'price_per_person',
        'total_price',
        'contact_name',
        'contact_phone',
        'contact_email',
        'special_requests',
        'status',
        'payment_method',
        'payment_proof',
        'paid_at',
        'confirmed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected $casts = [
        'price_per_person' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Auto-generate booking code
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'TRV-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // Helper: formatted total price
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Helper: status badge color
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'paid' => 'green',
            'completed' => 'emerald',
            'cancelled' => 'red',
            'refunded' => 'gray',
            default => 'gray',
        };
    }
}
