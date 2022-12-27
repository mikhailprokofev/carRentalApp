<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rental;

class Car extends Model
{
    use HasFactory;

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;

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

    public function getKeyType ()
    {
        return 'string';
    }

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
