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
        return $this->getDiscountedPriceForSize();
    }

    public function getDiscountedPriceForSize($size = null)
    {
        $discount = $this->best_discount;
        // If no size specified or product has no sizes, use base price
        if (!$size || !$this->prices()->exists()) {
            return intval($this->price - ($this->price * $discount / 100));
        }

        // Get price for specific size
        $sizePrice = $this->getPriceForSize($size);
    
        if ($sizePrice) {
            return intval($sizePrice - ($sizePrice * $discount / 100));
        }

        // Fallback to base price if size not found
        return intval($this->price - ($this->price * $discount / 100));
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function activePrices(): HasMany
    {
        return $this->prices()->where('is_active', true);
    }
    public function getSizes(): array
    {
        return $this->activePrices()->pluck('size')->toArray();
    }
    public function getPriceForSize(string $size): ?float
    {
        $priceRecord = $this->activePrices()->where('size', $size)->first();
        return $priceRecord ? $priceRecord->price : null;
    }
    public function getLowestPrice(): ?float
    {
        return $this->activePrices()->min('price');
    }

    public function getHighestPrice(): ?float
    {
        return $this->activePrices()->max('price');
    }

    public function getAllSizesWithPrices(): array
    {
        return $this->activePrices()->pluck('price', 'size')->toArray();
    }

    public function addSizePrice(string $size, float $price): ProductPrice
    {
        return $this->prices()->updateOrCreate(
            ['size' => $size],
            ['price' => $price, 'is_active' => true]
        );
    }

    public function removeSizePrice(string $size): bool
    {
        return $this->prices()->where('size', $size)->delete() > 0;
    }

    public function syncSizePrices(array $sizePrices): void
    {
        // Get current sizes from the database
        $currentSizes = $this->prices()->pluck('size')->toArray();
        info($currentSizes);
        // Get new sizes from the input
        $newSizes = array_column($sizePrices, 'size');
        info($newSizes);
        // Find sizes to remove (exist in DB but not in new data)
        $sizesToRemove = array_diff($currentSizes, $newSizes);

        // Remove sizes that are no longer needed
        if (!empty($sizesToRemove)) {
            $this->prices()->whereIn('size', $sizesToRemove)->delete();
        }

        // Add or update sizes
        foreach ($sizePrices as $sizePrice) {
            if (!empty($sizePrice['size']) && isset($sizePrice['price'])) {
                $this->addSizePrice($sizePrice['size'], $sizePrice['price']);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithPrices($query)
    {
        return $query->whereHas('activePrices');
    }
}
