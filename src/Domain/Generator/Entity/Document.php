<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\Entity;

use InvalidArgumentException;

readonly class Document
{
    public function __construct(private string $path)
    {
        if (!is_file($this->path)) {
            throw new InvalidArgumentException(sprintf('This file not exist: "%s"', $this->path));
        }
    }

    public function path(): string
    {
        return $this->path;
    }
}
