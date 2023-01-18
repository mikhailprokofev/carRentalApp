<?php

declare(strict_types=1);

namespace App\Module\Car\Utility;

final class NumberPlateCheck
{
    protected array $availableChars = ['A', 'B', 'K', 'M', 'H', 'O', 'P', 'C', 'T', 'Y', 'X'];

    protected array $invalidCombinationChars = [''];

    public static function isCorrect(string $numberPlate): bool
    {
        return true;
    }
}
