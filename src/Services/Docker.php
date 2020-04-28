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

class Docker implements Runnable
{
    private $runner;
    private $result;
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Runner $runner, Configuration $configuration)
    {
        $this->runner = $runner;
        $this->configuration = $configuration;
    }

    public function start($path)
    {
        $folder = "{$path}/{$this->configuration->folder()}";

        if (!File::exists($folder)) {
            throw new PathNotFoundException($folder);
        }

        $this->result = $this->runner->from($folder)->timeout(3600)->run([
            'docker-compose',
            'up',
            '-d',
            'nginx',
            'mysql',
            'workspace'
        ]);

        return $this->result->success();
    }

    public function error(): ?string
    {
        return "[{$this->result->code()}] {$this->result->error()}";
    }
}
