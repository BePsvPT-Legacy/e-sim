<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use crontab to process queues.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $i = 20;

        while ($i--) {
            $this->call('queue:work', [
                '--once' => true,
                '--memory' => 256,
                '--timeout' => 0,
            ]);
        }
    }
}
