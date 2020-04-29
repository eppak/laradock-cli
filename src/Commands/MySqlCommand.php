<?php namespace Eppak\Commands;

use Eppak\Exceptions\PathNotFoundException;
use Eppak\Runner\Runner as Run;
use Eppak\Services\Configuration;
use LaravelZero\Framework\Commands\Command;

class MySqlCommand extends Command
{
    use Runner;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysql {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql workspace';

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
     * @param Run $runner
     * @param Configuration $configuration
     * @return mixed
     * @throws PathNotFoundException
     */
    public function handle(Run $runner, Configuration $configuration)
    {
        $path = $this->path();
        $folder = "{$path}/{$configuration->folder()}";

        $runner->tty()->timeout(null)->from($folder)->run([
            'docker-compose',
            'exec',
            'mysql',
            'bash'
        ]);

        return 0;
    }
}
