<?php

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
