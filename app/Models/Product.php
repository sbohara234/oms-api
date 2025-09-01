<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'tenant_id',
        'description',
        'quantity',
        'product_code',
        'product_unit',
        'b2c_price_per_unit',
        'b2b_price_per_unit',
        'manufacturing_date',
        'expiry_date',
        'is_active',
    ];


    protected function casts(): array
    {
        return [
            'manufacturing_date' => 'date',
            'expiry_date' => 'date',
            'quantity' => 'integer'
        ];
    }

    /**
     * Get the tenant that owns the product.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
