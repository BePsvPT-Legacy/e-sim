<?php namespace App\Esim\Battle;

use App\Commands\CheckBattleExist;
use App\Esim\Citizen;
use App\Esim\Esim;
use App\Esim\GameDatum;
use App\Esim\MilitaryUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\FacadesCache;

class Battle extends Esim {

    protected $fillable = [
        'server',
        'battle_id',
        'round',
        'damage',
        'weapon',
        'berserk',
        'defender_side',
        'citizen_id',
        'citizenship',
        'military_unit',
        'time'
    ];

    public static function battle($server_name, $battle_id, $round = null)
    {
        $server_id = self::server_name_to_id($server_name);

        $data = Battle::select('damage', 'weapon', 'berserk', 'defender_side', 'citizen_id', 'citizenship', 'military_unit')
            ->battleId($battle_id)
            ->server($server_id);

        if ( ! is_null($round))
        {
            $data = $data->round($round);
        }

        $data = $data->get();

        if ( ! $data->count())
        {
            Bus::dispatch(new CheckBattleExist($server_id, $battle_id));

            return $data;
        }

        return self::parsing_battle_data($server_id, $data);
    }

    public static function mu($server_name, $battle_id)
    {
        $server_id = self::server_name_to_id($server_name);

        $data = Battle::select('damage', 'weapon', 'berserk', 'defender_side', 'military_unit')
            ->battleId($battle_id)
            ->server($server_id)
            ->where('military_unit', '!=', 0)
            ->get();

        if ( ! $data->count())
        {
            Bus::dispatch(new CheckBattleExist($server_id, $battle_id));

            return $data;
        }

        $result = [];
        
        foreach ($data as $datum)
        {
            if ( ! isset($result[$datum->military_unit]))
            {
                $result[$datum->military_unit] = [
                    '0' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    '1' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    'military_unit' => $datum->military_unit,
                    'military_unit_name' => MilitaryUnit::get_mu_name($server_id, $datum->military_unit),
                ];
            }

            $result[$datum->military_unit][(boolean) $datum->defender_side]['damage'] += $datum->damage;
            $result[$datum->military_unit][(boolean) $datum->defender_side]['weapon'][$datum->weapon] += (($datum->berserk) ? 5 : 1);
        }
        uasort($result, ['self', 'sort_cmp']);

        return $result;
    }

    public static function live($server_name, $battle_id)
    {
        /*
         * 初步判斷是否是已結束的戰場
         */
        $game_data = GameDatum::get_latest_battle_id($server_name);

        if (is_null($game_data) || intval($game_data->content) > intval($battle_id))
        {
            return null;
        }

        if (is_null($battleRoundId = self::get_battle_round_id($server_name, $battle_id)))
        {
            return null;
        }

        $data_key = $server_name . '_' . $battle_id . '_battle_data';

        $recatch = false;

        if (Cache::has($data_key))
        {
            $data = Cache::get($data_key);

            $time = $data['time'];

            if (5 < Carbon::now()->timestamp - $time)
            {
                $recatch = true;
            }
            else
            {
                $data = $data['data'];
            }
        }
        else
        {
            $recatch = true;
        }

        if ($recatch)
        {
            $data = self::curl_get('http://' . $server_name . '.e-sim.org/battleScore.html?id=' . $battleRoundId .'&at=50860&ci=32&premium=1');

            $data = json_decode($data);

            Cache::put($data_key, ['data' => $data, 'time' => Carbon::now()->timestamp], 1);
        }

        if (0 > $data->{'remainingTimeInSeconds'})
        {
            Cache::forget($server_name . '_' . $battle_id . '_battle_round_id');
        }

        return (is_null($data) || false === $data) ? null : $data;
    }

    public static function get_battle_round_id($server_name, $battle_id)
    {
        $round_key = $server_name . '_' . $battle_id . '_battle_round_id';

        if (is_null($battleRoundId = Cache::get($round_key, null)))
        {
            $battle_html = self::curl_get('http://' . $server_name . '.e-sim.org/battle.html?id=' . $battle_id, true, $server_name);

            $html = new \Htmldom($battle_html);

            if (is_null($html->root))
            {
                self::esim_login($server_name);

                return null;
            }

            if (is_null($battleRoundId = $html->find('input[id=battleRoundId]', 0)))
            {
                return null;
            }

            $battleRoundId = intval($battleRoundId->getAttribute('value'));

            Cache::put($round_key, $battleRoundId, 120);
        }

        return $battleRoundId;
    }

    public static function parsing_battle_data($server_id, $data)
    {
        $result = [];

        foreach ($data as $datum)
        {
            if ( ! isset($result[$datum->citizen_id]))
            {
                $result[$datum->citizen_id] = [
                    'citizen' => $datum->citizen_id,
                    'citizen_name' => Citizen::get_citizen_name($server_id, $datum->citizen_id),
                    '0' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    '1' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    'military_unit' => $datum->military_unit,
                    'military_unit_name' => MilitaryUnit::get_mu_name($server_id, $datum->military_unit),
                ];
            }

            $result[$datum->citizen_id][(boolean) $datum->defender_side]['damage'] += $datum->damage;
            $result[$datum->citizen_id][(boolean) $datum->defender_side]['weapon'][$datum->weapon] += (($datum->berserk) ? 5 : 1);
        }

        uasort($result, ['self', 'sort_cmp']);

        return $result;
    }

    public static function sort_cmp($a, $b)
    {
        $a_t = $a[0]['damage'] + $a[1]['damage'];
        $b_t = $b[0]['damage'] + $b[1]['damage'];

        if ($a_t == $b_t)
        {
            return 0;
        }

        return ($a_t > $b_t) ? -1 : 1;
    }

}
