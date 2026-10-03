<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    /**
     * Attributes that can be mass assigned.
     */
    protected $fillable = [
        'provider_id',
        'category_id',
        'name',
        'description',
        'duration_minutes',
        'price',
        'is_active',
    ];

    /**
     * Cast database values to appropriate PHP types.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    //Get the formatted service price.
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>
                'LKR ' . number_format((float) $attributes['price'], 2)
        );
    }

    //The service belongs to a service provider.
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * The service belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Return only active services.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Search services by name or description.
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $query, string $search) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        });
    }

    /**
     * Filter services by category.
     */
    public function scopeCategory(Builder $query, ?int $categoryId): Builder
    {
        return $query->when(
            $categoryId,
            fn(Builder $query) => $query->where('category_id', $categoryId)
        );
    }

    //booking
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    //Clean the service name before saving.
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => trim($value)
        );
    }

}