<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'category_id',
        'other_name',
        'other_description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all offers associated with this product
     */
    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class)
            ->withTimestamps(); // Include pivot timestamps
    }

    /**
     * Get only active offers for this product
     */
    public function activeOffers(): BelongsToMany
    {
        return $this->offers()->where('active', true);
    }
    /**
     * Get current valid offers (active and not expired)
     */
    public function currentOffers(): BelongsToMany
    {
        return $this->offers()
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
    /**
     * Get the best current discount percentage
     */
    public function getBestDiscountAttribute()
    {
        $bestOffer = $this->currentOffers()
            ->orderBy('sale_percentage', 'desc')
            ->first();

        return $bestOffer ? $bestOffer->sale_percentage : 0;
    }

    /**
     * Get discounted price
     */
    public function getDiscountedPriceAttribute()
    {
        $discount = $this->best_discount;
        return $this->price - ($this->price * $discount / 100);
    }
}
