<?php

namespace App\Domain\Reader\Entity;

readonly class Address
{
    public function __construct(private string $address, private string $city, private string $country, private string $postalCode)
    {
    }

    public function address(): string
    {
        return $this->address;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }
}