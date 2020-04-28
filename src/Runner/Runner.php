<?php namespace Eppak\Runner;

use Eppak\Exceptions\PathNotFoundException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use Eppak\Contracts\RunnerResult;
use Eppak\Contracts\Runner as RunnerInterface;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class Runner implements RunnerInterface
{
    private $useTty = false;
    private $path = null;
    private $timeout = 60;

    public function tty(): RunnerInterface
    {
        $this->useTty = true;

        return $this;
    }

    public function from(string $path): RunnerInterface
    {
        $this->path = $path;

        if (!file_exists($path)) {
            throw new PathNotFoundException($path);
        }

        return $this;
    }

    public function run(array $command): RunnerResult
    {
        $process = new Process($command, $this->path);

        //$process->disableOutput();
        $process->setTimeout($this->timeout);

        if ($this->tty()) {
            $process->setTty(true);
        }

        try {
            $process->mustRun();

            return new Response(
                $process->isSuccessful(),
                $process->getOutput(),
                $process->getExitCode(),
                $process->getErrorOutput()
            );

        } catch (ProcessFailedException $exception) {
            return new Response(
                false,
                $process->getOutput(),
                $process->getExitCode(),
                $process->getErrorOutput()
            );
        }
    }

    public function timeout(?int $timeout): RunnerInterface
    {
        $this->timeout = $timeout;

        return $this;
    }
}
