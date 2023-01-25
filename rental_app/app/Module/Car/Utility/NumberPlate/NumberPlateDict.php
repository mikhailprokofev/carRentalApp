<?php

declare(strict_types=1);

namespace App\Module\Car\Utility\NumberPlate;

final class NumberPlateDict
{
    public static array $availableChars = ['А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х'];

    public static array $regions = [
        'Moscow' => [77, 99, 97, 177, 199, 197],
    ];

    public static array $invalidCharsWithNumbs = [
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

    public static array $badCombinationChars = [
        'a5e0ff62be0b08456fc7f1e88812af3d',
        'b1563a78ec59337587f6ab6397699afc',
        '731c83db8d2ff01bdc000083fd3c3740',
    ];

    public static array $invalidCombinationChars = [
        'УУУ','ХХХ','ККК','ЕЕЕ','ННН','АМО','АМР','АОО','ВМР','ВОО','ЕКХ','ЕРЕ','ККХ','КММ','КМР','КОО','МММ','ММР',
        'МОО','ООО','РМР','САС','СММ','ССС','ТМР','УМР','ХКХ','СКО','ОМР','СОО', 'ААА', 'АММ',
    ];

    public static string $correctFormat = '/(?P<first>[А-Я]{1})(\d{3})(?P<other>[А-Я]{2})(\d{2,3})$/u';
}
