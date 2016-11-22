<?php namespace App\Commands;

use App\Commands\Command;

use App\Esim\MilitaryUnit;
use Illuminate\Contracts\Bus\SelfHandling;

class CatchMilitaryUnitData extends Command implements SelfHandling {

    protected $server_id, $mulitary_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($server_id = null, $mulitary_id = null)
    {
        $this->server_id = ((is_null($server_id)) ? null : (int) $server_id);
        $this->mulitary_id = ((is_null($mulitary_id)) ? null : (int) $mulitary_id);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $check_exist = MilitaryUnit::where('mu_id', '=', $this->mulitary_id)
            ->server($this->server_id);

        if ( ! $check_exist->count()) {

            $url = 'http://' . $this->server_id_to_name($this->server_id) . '.e-sim.org/apiMilitaryUnitById.html?id=' . $this->mulitary_id;

            $content = json_decode($this->curl_get($url));

            if (is_null($content) || false === $content || isset($content->{'error'}))
            {
                return;
            }

            $mu = new MilitaryUnit();
            $mu->server = $this->server_id;
            $mu->name = $content->{'name'};
            $mu->mu_id = $this->mulitary_id;
            $mu->save();
        }
    }

}
