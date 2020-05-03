<?php

namespace Tests\Feature;

use Eppak\Contracts\Runner;
use Eppak\Contracts\RunnerResult;
use Eppak\Services\Configuration;
use Eppak\Services\Git;
use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;

class GitTest extends TestCase
{
    public function testGitCloneOk()
    {
        $config = new Configuration();

        File::shouldReceive('exists')->with('somefolder')->andReturn('true');
        File::shouldReceive('exists')->with("somefolder/{$config->folder()}")->andReturn('true');

        $result = $mock = Mockery::mock(RunnerResult::class);
        $result->shouldReceive('success')->andReturn(true);

        $runner = $mock = Mockery::mock(Runner::class);
        $runner->shouldReceive('run')->andReturn($result);
        $runner->shouldReceive('from')->with('somefolder')->andReturn($runner);

        $git = new Git($runner, $config);
        $this->assertTrue($git->clone('somefolder'));
    }

    public function testGitCloneKo()
    {
        $config = new Configuration();

        File::shouldReceive('exists')->with('somefolder')->andReturn('true');
        File::shouldReceive('exists')->with("somefolder/{$config->folder()}")->andReturn('true');

        $result = $mock = Mockery::mock(RunnerResult::class);
        $result->shouldReceive('success')->andReturn(false);
        $result->shouldReceive('error')->andReturn('Some Error');
        $result->shouldReceive('code')->andReturn('127');

        $runner = $mock = Mockery::mock(Runner::class);
        $runner->shouldReceive('from')->with('somefolder')->andReturn($runner);
        $runner->shouldReceive('run')->andReturn($result);

        $git = new Git($runner, $config);
        $this->assertFalse($git->clone('somefolder'));
        $this->assertEquals($git->error(), '[127] Some Error');
    }
}
