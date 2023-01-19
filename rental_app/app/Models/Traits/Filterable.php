<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Filters\QueryFilter;

trait Filterable
{
    /**
     * @param Builder $builder
     * @param QueryFilter $filter
     */
    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}