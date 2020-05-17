<?php namespace Eppak\Commands;

use Exception;
use Illuminate\Support\Facades\Log;
use Eppak\Contracts\Runnable;
use Illuminate\Support\Str;
use Monolog\Registry as Monolog;

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
     * @param bool $error
     * @return bool
     */
    private function runTask(string $title, Runnable $runner, $function, bool $error = true): bool
    {
        Log::info($title);

        try {
            $done = $this->task($title, $function);

            if (!$done && $error) {
                Log::error($runner->error());

                $this->log();
            }

            return $done;

        } catch (Exception $e) {
            Log::error($e->getMessage());
            $this->log();
        }

        return false;
    }

    private function path()
    {
        $path = $this->option('path');

        if ($path == null) {
            $path = getcwd();
        }

        if (!Str::endsWith($path, '/')) {
            $path = rtrim($path, '/');
        }

        Log::notice("Running on path: {$path}");

        return $path;
    }

    private function log()
    {
        $log = app('log');
        $logfile = $log->driver()->getHandlers()[0]->getUrl();

        $this->warn("You can see detailed log in {$logfile}");
    }
}
