<?php namespace App\Commands;

use App\Commands\Command;

use App\Esim\GameDatum;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Queue;

class SyncLatestBattleId extends Command implements SelfHandling {

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $game_data = GameDatum::where('name', '=', 'latest_battle_id')->get();

        foreach ($game_data as $data)
        {
            if ('oriental' === $data->server) {
                continue;
            }

            $latest_id = $this->get_latest_battle_id($data->server);

            if (is_null($latest_id))
            {
                continue;
            }

            $data->content = $latest_id;
            $data->save();
        }

        Queue::later(Carbon::now()->addMinutes(90), new SyncLatestBattleId());
    }

    public function get_latest_battle_id($server)
    {
        $html = new \Htmldom('http://' . $server . '.e-sim.org/battles.html?countryId=-1&sorting=BY_START_TIME&_substidedOnly=on');

        $html = $html->find('table', 0);

        if (is_null($html))
        {
            return null;
        }

        $html = $html->find('a', 0);

        if (is_null($html))
        {
            return null;
        }

        $href = $html->getAttribute('href');

        $battle_id = substr($href, strpos($href, '=') + 1);

        return $battle_id;
    }

}
