<?php namespace Tests\Stub;

use Eppak\Services\Configuration;
use Mockery;
use Eppak\Contracts\Runner;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

class RunnerStub
{
    public static function make($result, $path)
    {
        $config = resolve(Configuration::class);
        $runner = $mock = Mockery::mock(Runner::class);

        $runner->shouldReceive( 'from' )->with( $path )->andReturnSelf();
        $runner->shouldReceive( 'from' )->with( "{$path}/{$config->folder()}" )->andReturnSelf();
        $runner->shouldReceive( 'tty' )->with( true )->andReturnSelf();
        $runner->shouldReceive( 'timeout' )->with( 3600 )->andReturnSelf();
        $runner->shouldReceive( 'timeout' )->with( 0 )->andReturnSelf();
        $runner->shouldReceive( 'run' )->andReturn($result);

        return $runner;
    }
}
