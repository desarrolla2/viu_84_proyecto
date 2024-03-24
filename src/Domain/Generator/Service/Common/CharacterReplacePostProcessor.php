<?php

namespace App\Domain\Generator\Service\Common;

use App\Domain\Generator\Service\PostProcessorInterface;
use App\Domain\Generator\ValueObject\Text;

class CharacterReplacePostProcessor implements PostProcessorInterface
{
    public static function order(): int
    {
        return 100;
    }

    public function execute(Text $text): Text
    {
        return new Text(str_replace("\f", "\n", $text->content()));
    }
}
