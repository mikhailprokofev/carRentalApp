<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

use App\Common\Enum\BinaryEnumInterface;
use App\Common\Enum\Traits\EnumToArray;

enum Model: string implements BinaryEnumInterface
{
    public const BINARY_INT = 1;

    use EnumToArray;

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

    // TODO: если для одной и той же модели будет два бренда то делать | или умножение
    public function getBinaryInt(): int
    {
        return match ($this) {
            // для примера поделено
            self::A3 => self::BINARY_INT << 0 | self::BINARY_INT << 3,
            self::A4, self::A5 => self::BINARY_INT << 0,
//            self::A3, self::A4, self::A5 => 2 << 0,
            self::LOGAN, self::SANDERO, self::DUSTER, self::ARKANA, self::KAPTUR => self::BINARY_INT << 3,
            self::GOLF, self::JETTA, self::PASSAT, self::POLO, self::TIGUAN => self::BINARY_INT << 1,
            self::GRANTA, self::LARGUS, self::NIVA, self::VESTA, self::XRAY => self::BINARY_INT << 2,
            self::CERATO, self::K5, self::K900, self::RIO, self::STINGER => self::BINARY_INT << 4,
            self::ACCORD, self::CAPA, self::CIVIC, self::CRV, self::PILOT => self::BINARY_INT << 5,
        };
    }
}