<?php namespace Eppak\Services;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class Configuration
{
    public function folder(): string
    {
        return "laradock";
    }

    public function php(): string
    {
        return "7.4";
    }

    public function mysql(): string
    {
        return "5.7";
    }

    public function name(): string
    {
        return "laradock-cli";
    }
}
