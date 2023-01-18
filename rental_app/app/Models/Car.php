<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * @param  mixed $value
     * @param  string|null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
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

    protected $appends = [
        'count_crashes',
    ];

    public function getKeyType(): string
    {
        return 'string';
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function crashes(): HasMany
    {
        return $this->hasMany(Crash::class);
    }

    public function getCountCrashesAttribute(): int
    {
        return $this->crashes()->getResults()->count() ;
    }
}
