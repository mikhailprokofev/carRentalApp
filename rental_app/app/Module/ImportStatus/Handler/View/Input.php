<?php

declare(strict_types=1);

namespace App\Module\ImportStatus\Handler\View;

use Illuminate\Http\Request;

final class Input
{
    public function __construct(
        private string $fileName,
    ) {}

    public static function make(Request $request): self
    {
        return new self(
            $request->get('filename'),
        );
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
