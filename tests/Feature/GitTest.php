<?php namespace Tests\Feature;

use Eppak\Exceptions\PathNotFoundException;
use Eppak\Services\Configuration;
use Eppak\Services\Git;
use Tests\Stub\FileStub;
use Tests\Stub\ResultStub;
use Tests\Stub\RunnerStub;
use Tests\TestCase;

class GitTest extends TestCase
{
    /**
     * @throws PathNotFoundException
     */
    public function testGitCloneOk()
    {
        $config = new Configuration();
        $file = new FileStub($config);

        $file->path('somefolder', true);

        $result = ResultStub::make(true);
        $runner = RunnerStub::make($result, 'somefolder');

        $git = new Git($runner, $config);
        $this->assertTrue($git->clone('somefolder'));
    }

    /**
     * @throws PathNotFoundException
     */
    public function testGitCloneKo()
    {
        $config = new Configuration();

        $file = new FileStub($config);
        $file->path('somefolder', true);

        $result = ResultStub::error();
        $runner = RunnerStub::make($result, 'somefolder');

        $git = new Git($runner, $config);
        $this->assertFalse($git->clone('somefolder'));
        $this->assertEquals('[127] Some Error', $git->error());
    }
}
