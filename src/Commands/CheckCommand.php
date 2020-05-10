<?php namespace Eppak\Commands;

use Eppak\Services\Docker;
use Illuminate\Console\Command;

class CheckCommand extends Command
{
    use Runner;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if all is ok';

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
        if (!sudo()) {
            $this->warn('Command need administrative privilege (root or sudo)');

            return 1;
        }

        $this->runTask("Docker process...", $docker, function () use ($docker)  {
            return $docker->ps();
        }, false);

        $this->table(['Name', 'Status'], $docker->status());

        return 0;
    }
}
