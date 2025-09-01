<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{

    protected $fillable =   [
        'user_id',
        'tenant_id',
        'customer_id',
        'order_number',
        'status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status',
        'paid_at',
        'notes'
    ];
    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime'
        ];
    }
    /**
     * Get the tenant that owns the Order.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the User that owns the Order.
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Customer that owns the Order.
     */
    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the orderItems for the order.
     */
    public function OrderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
