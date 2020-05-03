<?php namespace Eppak\Services;

use Eppak\Contracts\Runnable;
use Eppak\Contracts\Runner;

use Eppak\Exceptions\PathNotFoundException;
use Illuminate\Support\Facades\File;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

class Git implements Runnable
{
    /**
     * @var Runner
     */
    private $runner;
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var
     */
    private $result;

    /**
     * Git constructor.
     * @param Runner $runner
     * @param Configuration $configuration
     */
    public function __construct(Runner $runner, Configuration $configuration)
    {
        $this->runner = $runner;
        $this->configuration = $configuration;
    }

    /**
     * @param $path
     * @param bool $force
     * @return bool
     * @throws PathNotFoundException
     */
    public function clone($path, $force = false): bool
    {
        $folder = "{$path}/{$this->configuration->folder()}";

        if ($force && File::exists($folder)) {
            File::deleteDirectories($folder);
            File::deleteDirectory($folder, false);
        }

        if (!File::exists($path)) {
            throw new PathNotFoundException($path);
        }

        $this->result = $this->runner->from($path)->run([
           'git',
            'clone',
            'https://github.com/Laradock/laradock.git'
        ]);

        return $this->result->success();
    }

    /**
     * @return string|null
     */
    public function error(): ?string
    {
        return "[{$this->result->code()}] {$this->result->error()}";
    }
}
