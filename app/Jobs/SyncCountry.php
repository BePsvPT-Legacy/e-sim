<?php

namespace App\Jobs;

use App\Country;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncCountry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $server = ['primera', 'secura', 'suna'];

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->server as $server) {
            $url = sprintf('https://%s.e-sim.org/apiCountries.html', $server);

            $countries = fetch_json($url);

            foreach ($countries as $country) {
                Country::updateOrCreate([
                    'country_id' => $country['id'],
                ], [
                    'name' => $country['name'],
                    'code' => mb_strtolower($country['shortName']),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
