<?php

namespace RealPage\Marker;

use RealPage\Marker\Exceptions\FailedToClone;
use RealPage\Marker\Exceptions\FailedToPull;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Git
{
    const GITHUB_URL = 'https://github.com/';

    protected $process;

    public function __construct(Process $process = null)
    {
        $this->process = $process ?? new Process('');
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function clone(string $repository, string $parentDirectory)
    {
        $this->process->setCommandLine($this->buildCloneCommandString($repository, $parentDirectory));
        $this->process->disableOutput();
        $this->process->run();

        if (!$this->process->isSuccessful()) {
            throw new FailedToClone($this->process);
        }
    }

    public function pull(string $location): bool
    {
        $success = false;
        $this->process->setCommandLine($this->buildPullCommandString($location));
        $this->process->run(function ($type, $buffer) use (&$success) {
            if (Process::ERR === $type) {
                echo 'ERR > ' . $buffer;
            } else {
                echo 'OUT > ' . $buffer;
                if ($success === false) {
                    $success = stristr(strtolower($buffer), 'updating') !== false;
                }
            }
        });

        if (!$this->process->isSuccessful()) {
            throw new FailedToPull($this->process);
        }

        return $success;
    }

    protected function buildCloneCommandString(string $repository, string $parentDirectory): string
    {
        $repositoryPieces = explode('/', $repository);
        $cloneUrl         = 'git clone ' . self::GITHUB_URL . $repository . '.git';
        $localPath        = $parentDirectory . DIRECTORY_SEPARATOR . $repositoryPieces[0] . DIRECTORY_SEPARATOR . $repositoryPieces[1];

        return $cloneUrl . ' ' . $localPath;
    }

    protected function buildPullCommandString(string $location): string
    {
        return 'git -C ' . $location . ' pull';
    }
}
