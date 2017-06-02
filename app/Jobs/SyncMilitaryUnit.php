<?php

namespace App\Jobs;

use App\MilitaryUnit;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncMilitaryUnit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var int
     */
    protected $militaryUnitId;

    /**
     * Create a new job instance.
     *
     * @param string $server
     * @param int $militaryUnitId
     */
    public function __construct($server, $militaryUnitId)
    {
        $this->server = $server;

        $this->militaryUnitId = $militaryUnitId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = sprintf(
            'https://%s.e-sim.org/apiMilitaryUnitById.html?id=%d',
            $this->server,
            $this->militaryUnitId
        );

        $mu = fetch_json($url);

        MilitaryUnit::updateOrCreate([
            'server' => $this->server,
            'military_unit_id' => $this->militaryUnitId,
        ], [
            'name' => $mu['name'],
            'updated_at' => Carbon::now(),
        ]);
    }
}
