<?php

namespace App\Http\Controllers;

use App\Citizen;
use App\Country;
use App\Fight;
use App\MilitaryUnit;
use Cache;

class BattleController extends Controller
{
    /**
     * Entire battle fights statistics.
     *
     * @param string $server
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function entire($server, $id)
    {
        $fights = Cache::remember("battle-{$id}", 60, function () use ($server, $id) {
            $fights = Fight::where('server', $server)
                ->where('battle_id', $id)
                ->get();

            return $this->transformFights($fights);
        });

        return view('battles.index', compact('fights'));
    }

    /**
     * Specific round fights statistics.
     *
     * @param string $server
     * @param int $id
     * @param int $round
     *
     * @return \Illuminate\View\View
     */
    public function round($server, $id, $round)
    {
        $fights = Cache::remember("battle-{$id}-{$round}", 60, function () use ($server, $id, $round) {
            $fights = Fight::where('server', $server)
                ->where('battle_id', $id)
                ->where('round', $round)
                ->get();

            return $this->transformFights($fights);
        });

        return view('battles.index', compact('fights'));
    }

    /**
     * Entire battle military unit fights statistics.
     *
     * @param string $server
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function mu($server, $id)
    {
        $fights = Cache::remember("battle-{$id}-mu", 60, function () use ($server, $id) {
            $fights = Fight::where('server', $server)
                ->where('battle_id', $id)
                ->whereNotNull('military_unit_id')
                ->get();

            return $this->transformFights($fights, 'military_unit_id');
        });

        return view('battles.index', compact('fights'));
    }

    /**
     * Transform fights data to organized format.
     *
     * @param \Illuminate\Database\Eloquent\Collection $fights
     * @param string $group
     *
     * @return array
     */
    protected function transformFights($fights, $group = 'citizen_id')
    {
        $result = [];

        foreach ($fights as $fight) {
            if ( ! isset($result[$fight->{$group}])) {
                $result[$fight->{$group}] = $this->newRecord($fight);
            }

            $side = $fight->is_defender ? 'defender' : 'attacker';

            $result[$fight->{$group}]['round'][$fight->round]['damage'][$side] += $fight->damage;
            $result[$fight->{$group}][$side]['damage'] += $fight->damage;
            $result[$fight->{$group}][$side]['weapon'][$fight->weapon] += (($fight->is_berserk) ? 5 : 1);
        }

        return $result;
    }

    /**
     * Create a new fight record.
     *
     * @param Fight $fight
     *
     * @return array
     */
    protected function newRecord(Fight $fight)
    {
        return [
            'citizen' => [
                'id' => $fight->citizen_id,
                'name' => Citizen::name($fight->server, $fight->citizen_id),
                'citizenship' => Country::where('country_id', $fight->citizenship_id)->first()->code,
            ],
            'military_unit' => [
                'id' => $fight->military_unit_id,
                'name' => MilitaryUnit::name($fight->server, $fight->military_unit_id),
            ],
            'attacker' => ['damage' => 0, 'weapon' => array_fill(0, 6, 0)],
            'defender' => ['damage' => 0, 'weapon' => array_fill(0, 6, 0)],
            'round' => array_fill(1, 15, ['damage' => ['attacker' => 0, 'defender' => 0]]),
        ];
    }
}
