<?php

declare(strict_types=1);

namespace App\Module\Car\Tree;

use App\Module\Car\Enum\Country;

final class CarTree
{
//    private array $tree = [
//        'value' => 'nested',
//        'children' => [
//            [
//                'value' => 'nested',
//                'children' => [
//
//                ],
//            ],
//            [
//                'value' => 'nested',
//                'children' => [
//                    ['value' => 'fr'],
//                ],
//            ],
//            'fr' => 'reno'
//        ]
//    ];


    private array $car = [
        'country' => [
            'brand' => [
                'model' => [
                    'control',
                    'bodyType',
                    'transmission',
                    [
                      'class' => [
                          'price',
                      ]
                    ],
                ]
            ]
        ]
    ];
}
