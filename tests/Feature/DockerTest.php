<?php namespace Tests\Feature;

use Eppak\Services\Configuration;
use Eppak\Services\Docker;
use Tests\Stub\FileStub;
use Tests\Stub\ResultStub;
use Tests\Stub\RunnerStub;
use Tests\TestCase;

class DockerTest extends TestCase
{

    private function output($config)
    {
        $result = '';
        $processes = [ '_nginx_1',
            '_php-fpm_1',
            '_workspace_1',
            '_mysql_1',
            '_docker-in-docker_1'];

        foreach ($processes as $process) {
            $result .= "\"{$config->name()}{$process}\"\n";
        }

        return $result;
    }

    public function testDockerPs()
    {
        $config = new Configuration();

        $result = ResultStub::output($this->output($config));
        $runner = RunnerStub::make($result, 'somefolder');

        $docker = new Docker($runner, $config);

        $this->assertTrue($docker->ps());

        foreach ($docker->status() as $value) {
            $this->assertEquals('OK', $value['status']);
        }
    }

    public function testDockerStart()
    {
        $config = new Configuration();
        $file = new FileStub($config);

        $file->path('somefolder', true);

        $result = ResultStub::make(true);
        $runner = RunnerStub::make($result, 'somefolder');

        $docker = new Docker($runner, $config);

        $this->assertTrue($docker->start('somefolder'));
    }

    public function testDockerStop()
    {
        $config = new Configuration();
        $file = new FileStub($config);

        $file->path('somefolder', true);

        $result = ResultStub::make(true);
        $runner = RunnerStub::make($result, 'somefolder');

        $docker = new Docker($runner, $config);

        $this->assertTrue($docker->stop('somefolder'));
    }
}
