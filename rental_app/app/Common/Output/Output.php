<?php

declare(strict_types=1);

namespace App\Common\Output;

use JsonSerializable;

final class Output implements OutputInterface, JsonSerializable
{
    public function __construct(
        private array $data = [],
    ) {}

    public function set(string $key, mixed $data): self
    {
        $this->data[$key] = $data instanceof Output ? $data->getAll() : $data;

        return $this;
    }

    public function setCollection(array|Output $collection): self
    {
        $data = $collection instanceof Output ? $collection->getAll() : $collection;

        $this->data = array_merge_recursive($this->data, $data);

        return $this;
    }

    public function getAll(): array
    {
        return$this->data;
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }
}
