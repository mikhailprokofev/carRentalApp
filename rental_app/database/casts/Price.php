<?php

declare(strict_types=1);

namespace Database\Casts;

use App\Models\Car;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class Price implements CastsAttributes
{
    /**
     * Преобразовать значение к пользовательскому типу.
     *
     * @param Car $model
     * @param string $key
     * @param int $value
     * @param array $attributes
     * @return float
     */
    public function get($model, $key, $value, $attributes)
    {
        return (int) bcdiv((string) $value, '1', 2);
    }

    /**
     * Подготовить переданное значение к сохранению.
     *
     * @param Car $model
     * @param string $key
     * @param int|float $value
     * @param array $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return bcmul((string) $value, '100', 0);
    }
}
