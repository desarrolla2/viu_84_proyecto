<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

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
