<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class installProjectDependencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forSaleInter:install-project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to build docker image, install all project dependencies, migrate and seed database';

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
     * Helper function to run shell commands.
     */
    protected function runProcess($command)
    {
        $process = Process::fromShellCommandline($command);

        $process->setTimeout(null); // No timeout
        $process->run();

        // Check for errors
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Output the result
        $this->info($process->getOutput());
    }

    /**
     * Execute the console command.
     *
     * @return int
    */
    public function handle()
    {
        $this->runProcess('docker-compose build app');
        $this->runProcess('docker-compose up -d');
        $this->runProcess('docker exec -i db mysql -u root -e "CREATE DATABASE IF NOT EXISTS onsaleinter;"');
        $this->runProcess('composer install');
        $this->runProcess('composer update');
        $this->runProcess('cp .env.example .env');
        $this->runProcess('php artisan migrate --seed');
        $this->runProcess('php artisan config:cache');
        $this->runProcess('php artisan config:clear');
        $this->runProcess('php artisan cache:clear');
        $this->runProcess('php artisan key:generate');

        $this->info('All commands executed successfully!');

        return 0;
    }
}
