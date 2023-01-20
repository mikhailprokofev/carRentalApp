<?php

declare(strict_types=1);

namespace App\Module\Car\Dependency;

use App\Module\Car\Enum\Brand;
use App\Module\Car\Enum\Model;

final class EnumDependencies
{
    public static function getModelsByBrand(Brand $brand): array
    {
        return match ($brand) {
            Brand::AUDI => [Model::A3, Model::A4, Model::A5],
            Brand::RENAULT => [Model::LOGAN, Model::SANDERO, Model::DUSTER, Model::ARKANA, Model::KAPTUR],
            Brand::VOLKSWAGEN => [Model::GOLF, Model::JETTA, Model::PASSAT, Model::POLO, Model::TIGUAN],
            Brand::LADA => [Model::GRANTA, Model::LARGUS, Model::NIVA, Model::VESTA, Model::XRAY],
            Brand::KIA => [Model::CERATO, Model::K5, Model::K900, Model::RIO, Model::STINGER],
            Brand::HONDA => [Model::ACCORD, Model::CAPA, Model::CIVIC, Model::CRV, Model::PILOT],
        };
    }
}
