<?php namespace Eppak\Services;


use Eppak\Contracts\Runnable;
use Eppak\Exceptions\PathNotFoundException;
use Eppak\Runner\Runner;
use Illuminate\Support\Facades\File;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class Git implements Runnable
{
    private $runner;
    private $configuration;
    private $result;

    public function __construct(Runner $runner, Configuration $configuration)
    {
        $this->runner = $runner;
        $this->configuration = $configuration;
    }

    public function clone($path, $force = false): bool
    {
        $folder = "{$path}/{$this->configuration->folder()}";

        if (!File::exists($path)) {
            throw new PathNotFoundException($path);
        }

        if ($force && File::exists($folder)) {
            File::deleteDirectories($folder);
        }

        $this->result = $this->runner->from($path)->run([
           'git',
            'clone',
            'https://github.com/Laradock/laradock.git'
        ]);

        return $this->result->success();
    }

    public function error(): ?string
    {
        return "[{$this->result->code()}] {$this->result->error()}";
    }
}
