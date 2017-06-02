<?php

namespace App\Jobs;

use App\Citizen;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncCitizen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var int
     */
    protected $citizenId;

    /**
     * Create a new job instance.
     *
     * @param string $server
     * @param int $citizenId
     */
    public function __construct($server, $citizenId)
    {
        $this->server = $server;

        $this->citizenId = $citizenId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = sprintf(
            'https://%s.e-sim.org/apiCitizenById.html?id=%d',
            $this->server,
            $this->citizenId
        );

        $user = fetch_json($url);

        Citizen::updateOrCreate([
            'server' => $this->server,
            'citizen_id' => $this->citizenId,
        ], [
            'name' => $user['login'],
            'updated_at' => Carbon::now(),
        ]);
    }
}
