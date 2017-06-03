<?php

namespace App\Console\Commands;

use App\Citizen;
use App\Fight;
use App\MilitaryUnit;
use App\Model;
use Carbon\Carbon;
use Illuminate\Console\Command;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        collect()
            ->merge($this->getMissingCitizens())
            ->merge($this->getOutdatedCitizens())
            ->merge($this->getMissingMilitaryUnits())
            ->merge($this->getOutdatedMilitaryUnits())
            ->each(function (Model $model) {
                $attrs = $model->getAttributes();

                $name = isset($attrs['citizen_id']) ? 'Citizen' : 'MilitaryUnit';

                $syncName = sprintf('App\Jobs\Sync%s', $name);

                $idName = sprintf('%s_id', snake_case($name));

                $job = new $syncName($attrs['server'], $attrs[$idName]);

                dispatch($job);
            });
    }

    /**
     * 從 fights table 中過濾出不存在資料庫中的 citizen id.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getMissingCitizens()
    {
        return Fight::join('citizens', 'citizens.citizen_id', '=', 'fights.citizen_id', 'left')
            ->whereNull('citizens.citizen_id')
            ->select(['fights.server', 'fights.citizen_id'])
            ->distinct()
            ->get();
    }

    /**
     * 從 fights table 中過濾出不存在資料庫中的 military unit id.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getMissingMilitaryUnits()
    {
        return Fight::join('military_units', 'military_units.military_unit_id', '=', 'fights.military_unit_id', 'left')
            ->whereNull('military_units.military_unit_id')
            ->whereNotNull('fights.military_unit_id')
            ->select(['fights.server', 'fights.military_unit_id'])
            ->distinct()
            ->get();
    }

    /**
     * 取得超過一個月未更新的 citizen id.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOutdatedCitizens()
    {
        return Citizen::where('updated_at', '<=', Carbon::now()->subMonth())
            ->get(['server', 'citizen_id']);
    }

    /**
     * 取得超過一個月未更新的 military unit id.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOutdatedMilitaryUnits()
    {
        return MilitaryUnit::where('updated_at', '<=', Carbon::now()->subMonth())
            ->get(['server', 'military_unit_id']);
    }
}
