<?php

namespace App\Console\Commands;

use App\Equipment;
use App\Parameter;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ImportEquipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:equipment {server} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import equipment information from files.';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach ($this->files() as $file) {
            $equipments = json_decode($this->filesystem->get($file), true);

            foreach ($equipments as $equipment) {
                $info = $this->eqInfo($equipment);

                $model = Equipment::firstOrCreate($info['attributes'], $info['values']);

                if ($model->wasRecentlyCreated) {
                    $model->parameters()->saveMany($info['parameters']);
                }
            }
        }
    }

    /**
     * Get import files.
     *
     * @return array
     */
    protected function files()
    {
        $path = $this->path();

        if (false === $path) {
            return [];
        }

        return $this->filesystem->isDirectory($path)
            ? $this->filesystem->files($path)
            : [$path];

    }

    /**
     * Get import path.
     *
     * @return bool|string
     */
    protected function path()
    {
        $path = $this->argument('path');

        if (starts_with($path, ['../', './']) || ! str_contains($path, '/')) {
            $path = base_path($path);
        }

        return realpath($path);
    }

    /**
     * Retrieve equipment information.
     *
     * @param array $equipment
     *
     * @return array
     */
    protected function eqInfo($equipment)
    {
        static $mapping = [
            'Personal' => 'Personal Armor',
            'Weapon' => 'Weapon Upgrade',
            'Lucky' => 'Lucky Charm',
        ];

        $slot = title_case($equipment['slot']);

        return [
            'attributes' => [
                'server' => $this->argument('server'),
                'equipment_id' => $equipment['id'],
            ],
            'values' => [
                'quality' => title_case(str_replace('_', ' ', $equipment['quality'])),
                'slot' => $mapping[$slot] ?? $slot,
            ],
            'parameters' => $this->parameters($equipment['parameters']),
        ];
    }

    /**
     * Retrieve equipment parameters.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function parameters($parameters)
    {
        $collection = collect($parameters)
            ->unique(function ($item) {
                return $item['type'].$item['value'];
            })
            ->toArray();

        foreach ($collection as $parameter) {
            $info[] = Parameter::firstOrCreate([
                'type' => title_case(str_replace('_', ' ', $parameter['type'])),
                'value' => $parameter['value'],
            ]);
        }

        return $info ?? [];
    }
}
