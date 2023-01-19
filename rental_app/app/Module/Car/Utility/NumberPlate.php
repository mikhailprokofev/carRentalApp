<?php

declare(strict_types=1);

namespace App\Module\Car\Utility;

final class NumberPlate
{
    protected array $availableChars = ['А', 'В', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х'];

    protected array $regions = [
        'Moscow' => [77, 99, 97, 177, 199, 197],
    ];

    protected array $invalidCharsWithNumbs = [
        [
            'АМР' => [
                'regions' => '97',
            ],
        ],
        [
            'ООО' => [
                'regions' => '77',
            ],
        ],
        [
            '*ММ' => [
                'regions' => '77',
            ],
        ],
        [
            'АММ' => [
                'regions' => '99',
                'numbers' => '[1-50]'
            ],
        ],
    ];

    protected array $badCombinationChars = [
        'a5e0ff62be0b08456fc7f1e88812af3d',
        'b1563a78ec59337587f6ab6397699afc',
        '731c83db8d2ff01bdc000083fd3c3740',
    ];

    protected array $invalidCombinationChars = [
        'УУУ','ХХХ','ККК','ЕЕЕ','ННН','АМО','АМР','АОО','ВМР','ВОО','ЕКХ','ЕРЕ','ККХ','КММ','КМР','КОО','МММ','ММР',
        'МОО','ООО','РМР','САС','СММ','ССС','ТМР','УМР','ХКХ','СКО','ОМР','СОО', 'ААА', 'АММ',
    ];

    protected string $correctFormat = '/(?P<first>[А-Я]{1})(\d{3})(?P<other>[А-Я]{2})(\d{2,3})$/u';

    public function isCorrect(string $combination): bool
    {
        if ($isValid = $this->isValidFormat($combination, $this->correctFormat)) {
            $alphas = $this->takeOutAlphas($combination, $this->correctFormat);
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
        $diff = array_diff($chars, $this->availableChars);

        return !count($diff);
    }

    protected function isGoodCombinationChars(array $combination): bool
    {
        $combination = array_map(fn (string $char) => ord($char), $combination);
        $sum = array_sum($combination);
        $hash = md5((string) $sum);

        return !in_array($hash, $this->badCombinationChars);
    }

    protected function isValidCombinationChars(string $combination): bool
    {
        return !in_array($combination, $this->invalidCombinationChars);
    }

    protected function takeOutAlphas(string $combination, string $format): string
    {
        preg_match($format, $combination, $matches);

        return $matches['first'] . $matches['other'];
    }
}
