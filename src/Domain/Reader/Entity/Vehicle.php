<?php

namespace App\Domain\Reader\Entity;

readonly class Vehicle
{
    public function __construct(private string $make, private string $model, private string $licensePlate)
    {
    }

    public function make(): string
    {
        return $this->make;
    }

    public function model(): string
    {
        return $this->model;
    }

    public function licensePlate(): string
    {
        return $this->licensePlate;
    }
}