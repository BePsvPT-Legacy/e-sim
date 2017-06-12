<?php

namespace App\Http\Middleware;

use App\Battle;
use App\Jobs\SyncBattle;
use Closure;
use Illuminate\Http\Request;

class VerifyBattle
{
    /**
     * Supported servers.
     *
     * @var array
     */
    protected $servers = ['primera', 'secura', 'suna'];

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $server = $request->route('server');
        $battleId = intval($request->route('id'));

        abort_if(! in_array($server, $this->servers), 404);
        abort_if(! ($battleId > 0), 404);

        $battle = Battle::firstOrCreate([
            'server' => $server,
            'battle_id' => $battleId,
        ]);

        if ($battle->wasRecentlyCreated || ! $battle->round) {
            if ($battle->wasRecentlyCreated) {
                $this->createSyncBattleJob($battle);
            }

            return response()->view('battles.404');
        }

        $this->validateRound($request, $battle);

        view()->share('battle', $battle);

        return $next($request);
    }

    /**
     * Create battle sync job.
     *
     * @param Battle $battle
     */
    protected function createSyncBattleJob(Battle $battle)
    {
        $job = new SyncBattle($battle->server, $battle->battle_id);

        dispatch($job->onQueue('high'));
    }

    /**
     * Check battle round is valid.
     *
     * @param Request $request
     * @param Battle $battle
     */
    protected function validateRound(Request $request, Battle $battle)
    {
        if (! $request->routeIs('battle.round')) {
            return;
        }

        $round = intval($request->route('round'));

        abort_if($round < 1 || $round > $battle->round, 404);
    }
}
