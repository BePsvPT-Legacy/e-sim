<?php

namespace App\Jobs;

use App\Battle;
use Artisan;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncBattle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var int
     */
    protected $battleId;

    /**
     * @var Battle
     */
    protected $battle;

    /**
     * Create a new job instance.
     *
     * @param string $server
     * @param int $battleId
     */
    public function __construct($server, $battleId)
    {
        $this->server = $server;

        $this->battleId = $battleId;

        $this->battle = Battle::where('server', $this->server)
            ->where('battle_id', $this->battleId)
            ->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (false === ($round = $this->getBattleRound())) {
            return;
        }

        for ($i = 1; $i <= $round; ++$i) {
            $url = sprintf(
                'https://%s.e-sim.org/apiFights.html?battleId=%d&roundId=%d',
                $this->server,
                $this->battleId,
                $i
            );

            $this->insertToDB($i, fetch_json($url));
        }

        $this->battle->update(['round' => $round]);

        Artisan::queue('sync');
    }

    /**
     * Insert fights to database.
     *
     * @param int $round
     * @param array $fights
     */
    protected function insertToDB($round, $fights)
    {
        $queue = [];

        foreach ($fights as $fight) {
            $queue[] = [
                'server' => $this->server,
                'battle_id' => $this->battleId,
                'round' => $round,
                'citizen_id' => $fight['citizenId'],
                'citizenship_id' => $fight['citizenship'],
                'military_unit_id' => $fight['militaryUnit'] ?? null,
                'damage' => $fight['damage'],
                'weapon' => $fight['weapon'],
                'is_berserk' => $fight['berserk'],
                'is_defender' => $fight['defenderSide'],
                'time' => Carbon::createFromFormat('d-m-Y H:i:s:u', $fight['time']),
            ];
        }

        foreach (array_chunk($queue, 100) as $values) {
            DB::table('fights')->insert($values);
        }
    }

    /**
     * Get battle round, false if something wrong.
     *
     * @return bool|int
     */
    protected function getBattleRound()
    {
        $url = sprintf(
            'https://www.cscpro.org/%s/battle/%d.json',
            $this->server,
            $this->battleId
        );

        $battle = fetch_json($url);

        if (str_contains($battle['status'] ?? '', 'Won')) {
            return $battle['round'];
        }

        // delete current battle from battles.
        $this->battle->delete();

        return false;
    }
}
