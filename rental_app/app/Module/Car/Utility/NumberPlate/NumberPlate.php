<?php

declare(strict_types=1);

namespace App\Module\Car\Utility\NumberPlate;

final class NumberPlate
{
    public function isCorrect(string $combination): bool
    {
        if ($isValid = $this->isValidFormat($combination, NumberPlateDict::$correctFormat)) {
            $alphas = $this->takeOutAlphas($combination, NumberPlateDict::$correctFormat);
            $arrAlphas = mb_str_split($alphas);

            $isValid &= $this->isAvailableChars($arrAlphas);
            $isValid &= $this->isGoodCombinationChars($arrAlphas);
            $isValid &= $this->isValidCombinationChars($alphas);
        }

        return (bool) $isValid;
    }

    protected function isValidFormat(string $combination, string $format): bool
    {
        return (bool) preg_match($format, $combination);
    }

    protected function isAvailableChars(array $chars): bool
    {
        $diff = array_diff($chars, NumberPlateDict::$availableChars);

        return !count($diff);
    }

    protected function isGoodCombinationChars(array $combination): bool
    {
        $combination = array_map(fn (string $char) => ord($char), $combination);
        $sum = array_sum($combination);
        $hash = md5((string) $sum);

        return !in_array($hash, NumberPlateDict::$badCombinationChars);
    }

    protected function isValidCombinationChars(string $combination): bool
    {
        return !in_array($combination, NumberPlateDict::$invalidCombinationChars);
    }

    protected function takeOutAlphas(string $combination, string $format): string
    {
        preg_match($format, $combination, $matches);

        return $matches['first'] . $matches['other'];
    }
}
