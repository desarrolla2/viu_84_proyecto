<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

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
