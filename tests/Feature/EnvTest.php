<?php namespace Tests\Feature;

use Eppak\Services\Configuration;
use Eppak\Services\Env;
use Tests\Stub\FileStub;
use Tests\TestCase;

class EnvTest extends TestCase
{
    public function testEnvOk()
    {
        $config = new Configuration();
        $file = new FileStub($config);

        $file->path('somefolder', true);
        $file->move('somefolder');
        $file->get('somefolder');
        $file->put('somefolder');

        $env = new Env($config);

        $this->assertTrue($env->configure('somefolder'));
    }

    public function testEnvKo()
    {
        $config = new Configuration();
        $file = new FileStub($config);

        $file->path('somefolder', false);
        $file->move('somefolder');
        $file->get('somefolder');
        $file->put('somefolder');

        $env = new Env($config);

        $this->assertFalse($env->configure('somefolder'));
    }
}
