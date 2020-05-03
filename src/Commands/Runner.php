<?php namespace Eppak\Commands;

use Eppak\Contracts\Runnable;
use Illuminate\Support\Str;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

trait Runner
{
    /**
     * @param string $title
     * @param Runnable $runner
     * @param $function
     * @return bool
     */
    private function runTask(string $title, Runnable $runner, $function): bool
    {
        $done = $this->task($title, $function);

        if (!$done) {
            $this->error($runner->error());
        }

        return $done;
    }

    private function path()
    {
        $path = $this->option('path');

        if ($path == null) {
            $path = getcwd();
        }

        if (!Str::endsWith($path, '/')) {
            // $path = "{$path}/";
        }

        return $path;
    }
}
