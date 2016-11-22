<?php namespace App\Commands;

use App\Esim\Battle\BattleList;
use App\Commands\Command;

use App\Esim\Battle\Battle;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class CatchBattleData extends Command implements SelfHandling {

    protected $server_id, $battle_id;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct($server_id = null, $battle_id = null)
    {
        $this->server_id = ((is_null($server_id)) ? null : (int) $server_id);
        $this->battle_id = ((is_null($battle_id)) ? null : (int) $battle_id);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $data = [];
        $round = 1;

        /*
         * 抓取戰場資料
         */
        while (true)
        {
            $url = 'http://' . $this->server_id_to_name($this->server_id) . '.e-sim.org/apiFights.html?battleId=' . $this->battle_id . '&roundId=' . $round;

            $content = json_decode($this->curl_get($url));

            /*
             * 如果 decode 失敗或戰場回合異常則重新將本場戰役新增到佇列
             */
            if (is_null($content) || false === $content || ++$round > 17)
            {
                Queue::later(Carbon::now()->addMinutes(10), new CatchBattleData($this->server_id, $this->battle_id));

                return;
            }
            else if (isset($content->{'error'}))
            {
                break;
            }

            $data[] = $content;
        }

        DB::reconnect();

        /*
         * 更新回合數資料
         */
        $battle_list = BattleList::battleId($this->battle_id)
            ->server($this->server_id)
            ->first();

        if (is_null($battle_list))
        {
            return;
        }

        $battle_list->rounds = $round - 2;
        $battle_list->save();

        /*
         * 將戰役資料新增到資料庫
         */
        $round = 1;

        foreach ($data as $datum)
        {
            $rows = [];

            foreach ($datum as $json)
            {
                $time = Carbon::createFromFormat('d-m-Y G:i:s:u', $json->{'time'})->toDateTimeString();

                $rows[] = [
                    'server' => $this->server_id,
                    'battle_id' => $this->battle_id,
                    'round' => $round,
                    'damage' => $json->{'damage'},
                    'weapon' => $json->{'weapon'},
                    'berserk' => $json->{'berserk'},
                    'defender_side' => $json->{'defenderSide'},
                    'citizen_id' => $json->{'citizenId'},
                    'citizenship' => $json->{'citizenship'},
                    'military_unit' => ((isset($json->{"militaryUnit"})) ? $json->{"militaryUnit"} : 0 ),
                    'time' => $time
                ];
            }

            Battle::insert($rows);

            ++$round;
        }
    }

}