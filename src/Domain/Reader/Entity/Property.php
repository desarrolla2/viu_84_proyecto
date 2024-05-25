<?php

namespace App\Domain\Reader\Entity;

readonly class Property
{
    public function __construct(private Address $address)
    {
    }

    public function address(): Address
    {
        return $this->address;
    }
}