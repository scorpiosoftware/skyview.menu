<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'sale_percentage',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'active' => 'boolean',
        'sale_percentage' => 'decimal:2',
    ];

    /**
     * Get all products associated with this offer
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withTimestamps(); // Include pivot timestamps
    }

    /**
     * Scope for active offers
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for current offers (active and not expired)
     */
    public function scopeCurrent($query)
    {
        return $query->where('active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
    /**
     * Check if offer is currently valid
     */
    public function isValid(): bool
    {
        $now = now();
        return $this->active && $this->start_date <= $now && $this->end_date >= $now;
    }

    protected static function booted()
    {
        static::deleting(function ($offer) {
            $offer->products()->detach();
        });
    }
}
