<?php namespace Tests\Stub;

use Mockery;
use Eppak\Contracts\RunnerResult;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

class ResultStub
{
    public static function make($return)
    {
        $result = $mock = Mockery::mock(RunnerResult::class);
        $result->shouldReceive( 'success' )->andReturn($return);

        return $result;
    }

    public static function error()
    {
        $result = $mock = Mockery::mock(RunnerResult::class);

        $result->shouldReceive( 'success' )->andReturn( false);
        $result->shouldReceive('error' )->andReturn(  'Some Error' );
        $result->shouldReceive('code')->andReturn( '127' );

        return $result;
    }

    public static function output($data)
    {
        $result = $mock = Mockery::mock(RunnerResult::class);

        $result->shouldReceive( 'success' )->andReturn( true);
        $result->shouldReceive('error' )->andReturn(  null );
        $result->shouldReceive('code')->andReturn( '0' );
        $result->shouldReceive('output')->andReturn( $data );

        return $result;
    }
}
