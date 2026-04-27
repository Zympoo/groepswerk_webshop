<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'stripe_payment_intent_id',
        'stripe_session_id',
        'address_details',
    ];

    /**
     * Proper casting for Enum and JSON.
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_amount' => 'decimal:2',
            'address_details' => 'json',
        ];
    }

    /**
     * Relationship with user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with order details.
     */
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
