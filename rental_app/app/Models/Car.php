<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'number_plate',
        'color',
        'type',
        'description',
        'base_salary',
        'model',
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    public function rental()
    {
        return $this->hasMany(Rental::class);
    }
}
