<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

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
