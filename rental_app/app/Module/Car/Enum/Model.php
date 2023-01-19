<?php

declare(strict_types=1);

namespace App\Module\Car\Enum;

enum Model: string
{
    use \App\Common\Enum\EnumToArray;
    //AUDI
    case A3 = 'A3';
    case A4 = 'A4';
    case A5 = 'A5';

    //HONDA
    case ACCORD = 'accord';
    case CAPA = 'capa';
    case CIVIC = 'civic';
    case CRV = 'crv';
    case PILOT = 'pilot';

    //KIA
    case CERATO = 'cerato';
    case K5 = 'K5';
    case K900 = 'K900';
    case RIO = 'Rio';
    case STINGER = 'stinger';

    //LADA
    case GRANTA = 'granta';
    case LARGUS = 'largus';
    case NIVA = 'niva';
    case VESTA = 'vesta';
    case XRAY = 'X-Ray';

    //RENAULT
    case LOGAN = 'logan';
    case SANDERO = 'sandero';
    case DUSTER = 'duster';
    case ARKANA = 'arkana';
    case KAPTUR = 'kaptur';

    //VOLKSWAGEN
    case GOLF = 'golf';
    case JETTA = 'jetta';
    case PASSAT = 'passat';
    case POLO = 'polo';
    case TIGUAN = 'tiguan';

}