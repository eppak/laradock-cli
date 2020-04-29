<?php namespace Eppak\Commands;

use Eppak\Exceptions\PathNotFoundException;
use Eppak\Services\Configuration;
use Eppak\Services\Docker;
use Illuminate\Console\Command;
use Eppak\Runner\Runner as Run;

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
        $this->runTask("Docker process...", $docker, function () use ($docker)  {
            return $docker->ps();
        });

        $this->table(['Name', 'Status'], $docker->status());

        return 0;
    }
}
