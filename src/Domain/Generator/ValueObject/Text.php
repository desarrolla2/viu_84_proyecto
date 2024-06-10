<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\ValueObject;

readonly class Text
{
    public function __construct(private string $content = '')
    {
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function hash(): string
    {
        return hash('sha256', $this->content);
    }
}
