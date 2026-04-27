<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
        'is_featured',
    ];

    /**
     * Proper casting for price to ensure decimal precision.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'stock' => 'integer',
        ];
    }

    /**
     * Senior Reflex: Accessor for price formatting.
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => '€ ' . number_format($this->price, 2, ',', '.'),
        );
    }

    /**
     * Relationship with category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope for products that are in stock.
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('stock', '>', 0)->where('is_active', true);
    }
}
