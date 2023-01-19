<?php 

declare(strict_types=1);

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class QueryFilter
{
    protected array $request;

    protected Builder $builder;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = $field;
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }
    }

    protected function fields(): array
    {
        return array_filter(
            array_map('trim', $this->request)
        );
    }
}