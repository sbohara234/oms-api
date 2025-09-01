<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Check if a user is authenticated and has a tenant_id
        if ($tenantId = optional(Auth::user())->tenant_id) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}
