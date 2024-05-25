<?php

namespace App\Domain\Reader\Entity;

readonly class Person
{
    public function __construct(private string $name, private string $surname, private string $number)
    {
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function number(): string
    {
        return $this->number;
    }
}
