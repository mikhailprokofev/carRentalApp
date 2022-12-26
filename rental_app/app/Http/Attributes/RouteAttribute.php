<?php

declare(strict_types=1);

namespace App\Http\Attributes;

#[
    \Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)
]
class RouteAttribute
{
    public function __construct(
        ?string $description = null,
        ?string $summary = null,
    ) {
    }
}