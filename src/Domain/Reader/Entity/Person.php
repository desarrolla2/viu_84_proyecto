<?php

namespace App\Domain\Reader\Entity;

readonly class Person
{
    public function __construct(private string $name, private string $number)
    {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function toArray(): array
    {
        return ['name' => $this->name(), 'number' => $this->number()];
    }
}
