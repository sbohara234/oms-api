<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use  Sluggable,HasFactory;


    protected $fillable = [
        'name',
        'domain',
        'slug',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the users for the tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the route key for the model (for implicit model binding using 'slug').
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    
}
