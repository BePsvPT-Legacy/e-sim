<?php namespace App\Commands;

use App\Esim\Battle\BattleList;
use App\Commands\Command;
use App\Esim\GameDatum;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class CheckBattleExist extends Command implements SelfHandling {

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
        /*
         * 檢查資料是否正確
         */
        if (is_null($this->server_id) || is_null($this->battle_id))
        {
            return;
        }

        /*
         * 檢查該場資料是否已存在
         */
        $check_exist = BattleList::server($this->server_id)
            ->battleId($this->battle_id)
            ->lockForUpdate();

        if ($check_exist->count())
        {
            return;
        }

        /*
         * 檢查目前最新戰場編號
         */
        $battle_id = GameDatum::where('name', '=', 'latest_battle_id')
            ->where('server', '=', $this->server_id_to_name($this->server_id))
            ->first();

        if (is_null($battle_id) || $this->battle_id >= (int) $battle_id->content || $this->battle_id < (int) $battle_id->content - 100)
        {
            return;
        }

        try
        {
            DB::transaction(function()
            {
                $battle = new BattleList();
                $battle->server = $this->server_id;
                $battle->battle_id = $this->battle_id;
                $battle->save();

                $check_duplicate = BattleList::server($this->server_id)
                    ->battleId($this->battle_id)
                    ->lockForUpdate();

                if (1 !== $check_duplicate->count())
                {
                    throw new ModelNotFoundException;
                }

                Queue::push(new CatchBattleData($this->server_id, $this->battle_id));
            });
        }
        catch (ModelNotFoundException $e)
        {
            Log::error('duplicate', [
                'source' => 'battle id',
                'server' => $this->server_id,
                'battle_id' => $this->battle_id
            ]);
        }
    }

}
