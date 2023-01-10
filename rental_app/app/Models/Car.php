<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

final class Car extends Model
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
     * Получить модель для привязанного к маршруту значения параметра.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (!Uuid::isValid($value)) {
            return $this->where('number_plate', $value)->firstOrFail();
        } else {
            return $this->where('id', $value)->firstOrFail();
        }
    }

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    public function getKeyType()
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
