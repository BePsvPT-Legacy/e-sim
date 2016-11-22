<?php

class Battle extends Eloquent {

    static $server_id = ['primera' => 1, 'secura' => 2, 'suna' => 3];

    public static function battle_data($server, $id, $round = null) {
        $data = Battle::select('damage', 'weapon', 'berserk', 'defender_side', 'citizen_id', 'citizenship', 'military_unit')
            ->where('server', '=', self::$server_id[$server])
            ->where('battle_id', '=', $id);
        if (null !== $round) {
            $data = $data->where('round', '=', $round);
        }
        $data = $data->get();

        return self::battle_data_parsing($data);
    }

    public static function battle_data_mu($server, $id) {
        $data = Battle::select('damage', 'weapon', 'berserk', 'defender_side', 'military_unit')
            ->where('server', '=', self::$server_id[$server])
            ->where('battle_id', '=', $id)
            ->get();

        $result = [];
        foreach ($data as $tmp) {
            if ($tmp->military_unit == 0) {
                continue;
            } else if ( ! isset($result[$tmp->military_unit])) {
                $result[$tmp->military_unit] = [
                    '0' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    '1' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    'military_unit' => $tmp->military_unit,
                ];
            }
            $result[$tmp->military_unit][$tmp->defender_side]['damage'] += $tmp->damage;
            $result[$tmp->military_unit][$tmp->defender_side]['weapon'][$tmp->weapon] += (($tmp->berserk) ? 5 : 1);
        }
        uasort($result, ['self', 'sort_cmp']);

        return $result;
    }

    public static function battle_data_parsing($data) {
        $result = [];
        foreach ($data as $tmp) {
            if ( ! isset($result[$tmp->citizen_id])) {
                $result[$tmp->citizen_id] = [
                    'citizen' => $tmp->citizen_id,
                    '0' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    '1' => [
                        'damage' => 0,
                        'weapon' => [0, 0, 0, 0, 0, 0],
                    ],
                    'military_unit' => $tmp->military_unit,
                ];
            }
            $result[$tmp->citizen_id][$tmp->defender_side]['damage'] += $tmp->damage;
            $result[$tmp->citizen_id][$tmp->defender_side]['weapon'][$tmp->weapon] += (($tmp->berserk) ? 5 : 1);
        }
        uasort($result, ['self', 'sort_cmp']);

        return $result;
    }

    public static function sort_cmp($a, $b) {
        $a_t = $a[0]['damage'] + $a[1]['damage'];
        $b_t = $b[0]['damage'] + $b[1]['damage'];
        if ($a_t == $b_t) {
            return 0;
        }
        return ($a_t > $b_t) ? -1 : 1;
    }

}
