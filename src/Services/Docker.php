<?php namespace Eppak\Services;


use Eppak\Contracts\Runnable;
use Eppak\Exceptions\FileNotFoundException;
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
    private $names = [];
    private $expected = [
        '_nginx_1',
        '_php-fpm_1',
        '_workspace_1',
        '_mysql_1',
        '_docker-in-docker_1'
    ];

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
        return $this->run($path, [
            'docker-compose',
            'up',
            '-d',
            'nginx',
            'mysql',
            'workspace'
        ]);
    }

    public function stop($path)
    {
        return $this->run($path, [
            'docker-compose',
            'stop',
            'nginx',
            'mysql',
            'php-fpm',
            'docker-in-docker',
            'workspace'
        ]);
    }

    private function run($path, $commands)
    {
        $folder = "{$path}/{$this->configuration->folder()}";

        if (!File::exists($folder)) {
            throw new PathNotFoundException($folder);
        }

        if (!File::exists("{$folder}/.env")) {
            throw new FileNotFoundException("{$folder}/.env");
        }

        $this->result = $this->runner->from($folder)->timeout(3600)->run($commands);

        return $this->result->success();
    }

    public function ps(): bool
    {
        $this->names = [];

        $this->result = $this->runner->run([
            'docker',
            'ps',
            '--format',
            '"{{.Names}}"'
        ]);

        if (!$this->result->success()) {
            return false;
        }

        $count = 0;
        foreach (explode("\n", $this->result->output()) as $name) {
            if ($name != null) {
                $this->names[$name] = $this->exists($name);

                if ($this->names[$name] ) {
                    $count++;
                }
            }
        }

        return (count($this->expected) == $count);
    }

    public function error(): ?string
    {
        return "Error code [{$this->result->code()}] {$this->result->error()}";
    }

    public function status(): array
    {
        $names = [];

        foreach ($this->names as $key => $value) {
            $names[] = [ 'name' => $key, 'status' => $value ? 'OK' : 'KO'];
        }

        return $names;
    }

    private function exists($name): bool {
        foreach ($this->expected as $item) {
            if("\"{$this->configuration->name()}{$item}\"" == $name) {
                return true;
            }
        }

        return false;
    }
}
