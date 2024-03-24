<?php

namespace App\Domain\Generator\Service\Dummy;

use App\Domain\Generator\Service\PostProcessorInterface;
use App\Domain\Generator\ValueObject\Text;

class DummyPostProcessor implements PostProcessorInterface
{
    public static function order(): int
    {
        return 0;
    }

    public function execute(Text $text): Text
    {
        return $text;
    }
}
