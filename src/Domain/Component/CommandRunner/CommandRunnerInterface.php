<?php

namespace App\Domain\Component\CommandRunner;

interface CommandRunnerInterface
{
    public function run(array $command): string;
}
