<?php

declare(strict_types=1);

namespace App\Module\File\Entity;

use App\Common\Type\Price;
use Ramsey\Uuid\UuidInterface;

final class Car
{
    public function __construct(
        private UuidInterface $id,
        private string $numberPlate,
        private string $description,
        private Price $baseSalary,
        private string $model,
    ) {}

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getNumberPlate(): string
    {
        return $this->numberPlate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBaseSalary(): Price
    {
        return $this->baseSalary;
    }

    public function getModel(): string
    {
        return $this->model;
    }


}
