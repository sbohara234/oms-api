<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',        
        'tenant_id',
        'email',
        'type'
    ];

    /**
     * Get the tenant that owns the product.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isB2B(): bool
    {
        return $this->type === 'b2b';
    }

    public function isB2C(): bool
    {
        return $this->type === 'b2c';
    }
}
