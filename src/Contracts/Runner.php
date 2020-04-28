<?php namespace Eppak\Contracts;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

interface Runner
{
    public function tty(): Runner;
    public function timeout(?int $timeout): Runner;
    public function from(string $path): Runner;
    public function run(array $command): RunnerResult;
}
