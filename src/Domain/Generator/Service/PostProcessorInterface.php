<?php

namespace App\Domain\Generator\Service;

use App\Domain\Generator\ValueObject\Text;

interface PostProcessorInterface
{
    public static function order(): int;

    public function execute(Text $text): Text;
}
