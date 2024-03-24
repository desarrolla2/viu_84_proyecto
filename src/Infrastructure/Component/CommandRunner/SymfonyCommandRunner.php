<?php

namespace App\Infrastructure\Component\CommandRunner;

use App\Domain\Component\CommandRunner\CommandRunnerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SymfonyCommandRunner implements CommandRunnerInterface
{
    public function run(array $command): string
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
