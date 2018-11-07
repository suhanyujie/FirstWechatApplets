<?php

namespace App\Console\Commands\Nqj;

use Illuminate\Console\Command;
use App\Services\Nqj\ContractTaskService;

class ContractTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nqj:contractTask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var ContractTaskService
     */
    protected $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new ContractTaskService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->service->taskConsume();
        var_dump($result);
    }
}
