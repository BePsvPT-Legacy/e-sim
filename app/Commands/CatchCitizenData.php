<?php namespace App\Commands;

use App\Commands\Command;

use App\Esim\Citizen;
use Illuminate\Contracts\Bus\SelfHandling;

class CatchCitizenData extends Command implements SelfHandling {

    protected $server_id, $citizen_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($server_id = null, $citizen_id = null)
    {
        $this->server_id = ((is_null($server_id)) ? null : (int) $server_id);
        $this->citizen_id = ((is_null($citizen_id)) ? null : (int) $citizen_id);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $check_exist = Citizen::where('citizen_id', '=', $this->citizen_id)
            ->server($this->server_id);

        if ( ! $check_exist->count()) {

            $url = 'http://' . $this->server_id_to_name($this->server_id) . '.e-sim.org/apiCitizenById.html?id=' . $this->citizen_id;

            $content = json_decode($this->curl_get($url));

            if (is_null($content) || false === $content || isset($content->{'error'}))
            {
                return;
            }

            $citizen = new Citizen();
            $citizen->server = $this->server_id;
            $citizen->name = $content->{'login'};
            $citizen->citizen_id = $this->citizen_id;
            $citizen->organization = $content->{'organization'};
            $citizen->save();
        }
    }

}
