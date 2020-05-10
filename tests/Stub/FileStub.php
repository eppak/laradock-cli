<?php namespace Tests\Stub;

use Eppak\Services\Configuration;
use Illuminate\Support\Facades\File;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class FileStub
{
    private $configuration;

    private function env($path)
    {
        return "{$path}/{$this->configuration->folder()}/.env";
    }

    private function envExample($path)
    {
        return "{$path}/{$this->configuration->folder()}/env-example";
    }

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function path($path, $result)
    {
        File::shouldReceive('exists')->with($path)->andReturn($result);
        File::shouldReceive('exists')->with("{$path}/{$this->configuration->folder()}")->andReturn($result);

        File::shouldReceive('exists')->with($this->env($path))->andReturn($result);
        File::shouldReceive('exists')->with($this->envExample($path))->andReturn($result);
    }

    public function move($path)
    {
        File::shouldReceive('move')->with( $this->envExample($path), $this->env($path))->andReturnTrue();
    }

    public function get($path)
    {
        File::shouldReceive('get')->with($this->env($path))->andReturn("PHP_VERSION=7.3\nCOMPOSE_PROJECT_NAME=test\nMYSQL_VERSION=latest\n");
    }

    public function put($path)
    {
        $content = "PHP_VERSION={$this->configuration->php()}\nCOMPOSE_PROJECT_NAME={$this->configuration->name()}\nMYSQL_VERSION={$this->configuration->mysql()}\n";

        File::shouldReceive('put')->with($this->env($path), $content)->andReturnTrue();
    }
}
