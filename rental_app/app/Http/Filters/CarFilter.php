<?php

namespace App\Http\Filters;

use App\Module\Car\Enum as Enums;

class CarFilter extends QueryFilter
{
    public function country(string $country)
    {
        $this->builder->where('country', $country);
    }

    public function brand(string $brand)
    {
        $this->builder->where('brand', $brand);
    }

    public function model(string $model)
    {
        $this->builder->where('model', $model);
    }

    public function color(string $color)
    {
        $this->builder->where('color', $color);
    }

    public function manufacture_date(string $manufacture_date)
    {
        $this->builder->where('manufacture_date', $manufacture_date);
    }

    public function min_manufacture_date(string $manufacture_date)
    {
        $this->builder->where('manufacture_date', '>' ,$manufacture_date);
    }

    public function max_manufacture_date(string $manufacture_date)
    {
        $this->builder->where('manufacture_date', '<', $manufacture_date);
    }

    public function mileage(string $mileage)
    {
        $this->builder->where('mileage', $mileage);
    }

    public function min_mileage(string $mileage)
    {
        $this->builder->where('mileage','>', $mileage);
    }

    public function max_mileage(string $mileage)
    {
        $this->builder->where('mileage','<', $mileage);
    }

    public function drive(string $drive)
    {
        $this->builder->where('drive', $drive);
    }

    public function control(string $value)
    {
        $control = Enums\Control::isRight($value) ;
        $this->builder->where('control', $control);
    }

    public function body_type(string $body_type)
    {
        $this->builder->where('body_type', $body_type);
    }

    public function transmission(string $transmission)
    {
        $this->builder->where('transmission', $transmission);
    }

    public function insurance(string $insurance)
    {
        $this->builder->where('insurance', $insurance);
    }

    public function type(string $type)
    {
        $this->builder->where('type', $type);
    }
}