<?php

namespace App\Infrastructure\Generator\Service\UTF8;

use App\Domain\Generator\Service\PostProcessorInterface;
use App\Domain\Generator\ValueObject\Text;
use ForceUTF8\Encoding;

class UTF8PostProcessor implements PostProcessorInterface
{
    public static function order(): int
    {
        return 100;
    }

    public function execute(Text $text): Text
    {
        return new Text(Encoding::fixUTF8($text->content()));
    }
}
