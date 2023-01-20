<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\Traits\EnumToArray;

enum Model: string
{
    use EnumToArray;

    //AUDI
    case A3 = 'A3';
    case A4 = 'A4';
    case A5 = 'A5';

    case ACCORD = 'accord';
    case CAPA = 'capa';
    case CIVIC = 'civic';
    case CRV = 'crv';
    case PILOT = 'pilot';

    case CERATO = 'cerato';
    case K5 = 'K5';
    case K900 = 'K900';
    case RIO = 'Rio';
    case STINGER = 'stinger';

    case GRANTA = 'granta';
    case LARGUS = 'largus';
    case NIVA = 'niva';
    case VESTA = 'vesta';
    case XRAY = 'X-Ray';

    case LOGAN = 'logan';
    case SANDERO = 'sandero';
    case DUSTER = 'duster';
    case ARKANA = 'arkana';
    case KAPTUR = 'kaptur';

    case GOLF = 'golf';
    case JETTA = 'jetta';
    case PASSAT = 'passat';
    case POLO = 'polo';
    case TIGUAN = 'tiguan';

    public static function getModelsByBrand(Brand $brand): array
    {
        return match ($brand) {
            Brand::AUDI => [self::A3, self::A4, self::A5],
            Brand::RENAULT => [self::LOGAN, self::SANDERO, self::DUSTER, self::ARKANA, self::KAPTUR],
            Brand::VOLKSWAGEN => [self::GOLF, self::JETTA, self::PASSAT, self::POLO, self::TIGUAN],
            Brand::LADA => [self::GRANTA, self::LARGUS, self::NIVA, self::VESTA, self::XRAY],
            Brand::KIA => [self::CERATO, self::K5, self::K900, self::RIO, self::STINGER],
            Brand::HONDA => [self::ACCORD, self::CAPA, self::CIVIC, self::CRV, self::PILOT],
        };
    }
}