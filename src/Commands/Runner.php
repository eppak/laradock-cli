<?php namespace Eppak\Commands;

use Eppak\Contracts\Runnable;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

trait Runner
{
    private function runTask(string $title, Runnable $runner, $function): bool
    {
        $done = $this->task($title, $function);

        if (!$done) {
            $this->error($runner->error());
        }

        return $done;
    }
}
