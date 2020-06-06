<?php

namespace App\Console\Commands\Test;

use App\Models\Test\TestArticleModel;
use Illuminate\Console\Command;

class TestMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:connect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试数据库链接';

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
     * @return mixed
     */
    public function handle()
    {
        $this->connect();
    }


    /**
     * @desc
     */
    public function connect()
    {
        $model = new TestArticleModel();
        var_dump($model);die;
    }
}
