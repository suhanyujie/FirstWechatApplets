<?php

namespace App\Console\Commands\Nqj;

use Illuminate\Console\Command;
use App\Services\Nqj\ContractTaskService;
use Workerman\Worker;
use Workerman\Lib\Timer;

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
        $logstr = date('Y-m-d H:i:s')." --> ".json_encode($result,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
        $logFile = storage_path('logs/nqjContractLog.log');
        if (!file_exists($logFile)) {
            touch($logFile);
            chmod($logFile,0777);
        }
        file_put_contents($logFile,$logstr,FILE_APPEND);
        var_dump($result);
    }

    public function setTimer()
    {
        $task = new Worker();
        $task->onWorkerStart = function($task)
        {
            // 2.5 seconds
            $time_interval = 2.5;
            $timer_id = Timer::add($time_interval, function () {
                echo "Timer run\n";
                $result = $this->service->taskConsume();
                $logstr = date('Y-m-d H:i:s')."-->".json_encode($result,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
                $logFile = storage_path('logs/nqjContractLog.log');
                if (!file_exists($logFile)) {
                    touch($logFile);
                    chmod($logFile,0777);
                }
                file_put_contents($logFile,$logstr,FILE_APPEND);
                var_dump($result);
            });
        };
        // run all workers
        Worker::runAll();
    }
}
