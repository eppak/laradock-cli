<?php namespace Eppak\Commands;

use Eppak\Services\Docker;
use LaravelZero\Framework\Commands\Command;

class StartCommand extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Laradock';

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
     * @param Docker $docker
     * @return mixed
     */
    public function handle(Docker $docker)
    {
        $path = $this->path();

        if (!sudo()) {
            $this->warn('Command need administrative privilege (root or sudo)');

            return 1;
        }

        $this->runTask("Starting Docker...", $docker, function () use ($docker, $path) {
            return $docker->start($path);
        });

        return 0;
    }
}
