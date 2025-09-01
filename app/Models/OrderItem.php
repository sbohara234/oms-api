<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
     
    protected $fillable = [
            'tenant_id',
            'order_id',
            'product_id',
            'quantity',
            'unit_price',
            'total_price',
            'tax_amount',
            'discount_amount'
        ];

        /**
     * Get the tenant that owns the OrderItem.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the Order that owns the OrderItem.
     */
    public function Order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the Product that owns the OrderItem.
     */
    public function Product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
