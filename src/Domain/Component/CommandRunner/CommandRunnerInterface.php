<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Component\CommandRunner;

interface CommandRunnerInterface
{
    /** @param string[] $command */
    public function run(array $command): string;
}
