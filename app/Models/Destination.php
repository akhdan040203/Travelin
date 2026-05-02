<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'location',
        'province',
        'price',
        'duration_days',
        'featured_image',
        'included',
        'excluded',
        'itinerary',
        'latitude',
        'longitude',
        'is_featured',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'included' => 'array',
        'excluded' => 'array',
        'itinerary' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(DestinationGallery::class)->orderBy('sort_order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function availableSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class)
            ->where('status', 'open')
            ->where('departure_date', '>=', now())
            ->orderBy('departure_date');
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, Schedule::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Helper: average rating
    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    // Helper: review count
    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    // Helper: formatted price
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
