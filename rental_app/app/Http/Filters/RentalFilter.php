<?php

namespace App\Http\Filters;

use App\Module\Car\Enum as Enums;

class CarFilter extends QueryFilter
{
    public function country(string $country)
    {
        $this->builder->where('country', $country);
    }
}