<?php namespace Eppak\Commands;

use Eppak\Services\Env;
use Eppak\Services\Git;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init {--path=} {?--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize repository';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Git $git
     * @param Env $env
     * @return mixed
     */
    public function handle(Git $git, Env $env)
    {
        $path = $this->option('path');
        $force = $this->option('force');

        $done = $this->runTask("Cloning to {$path}...", $git, function () use ($git, $path, $force) {
            if ($git->clone($path, $force)) {
                return true;
            }

            return false;
        });

        if (!$done) {
            return 1;
        }

        $this->runTask("Env...", $env, function () use ($env, $path, $force) {

            if($env->configure($path)) {
                return true;
            }

            return false;
        });

        return 0;
    }
}
