<?php

namespace App\Jobs;

use App\Equipment;
use App\Parameter;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Sunra\PhpSimple\HtmlDomParser;

class SyncEquipment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const BATCH = 250;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $server;

    /**
     * @var int
     */
    protected $beginId;

    /**
     * Create a new job instance.
     *
     * @param string $server
     * @param int $beginId
     */
    public function __construct($server, $beginId)
    {
        $this->client = new Client;

        $this->server = $server;

        $this->beginId = $beginId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->client->post(
            sprintf('https://%s.e-sim.org/previewMessage.html', $this->server),
            ['form_params' => ['title' => 'title', 'body' => $this->body()]]
        );

        $dom = HtmlDomParser::str_get_html($response->getBody()->getContents());

        foreach ($dom->find('img') as $img) {
            $info = $this->info($img);

            $equipment = Equipment::firstOrCreate($info['attributes'], $info['values']);

            if ($equipment->wasRecentlyCreated) {
                $equipment->parameters()->saveMany($info['parameters']);
            }
        }
    }

    /**
     * Generate request form body.
     *
     * @return string
     */
    protected function body()
    {
        $end = $this->beginId + self::BATCH;

        $content = '';

        for ($i = $this->beginId; $i < $end; ++$i) {
            $content .= "[equipment]{$i}[/equipment]";
        }

        return $content;
    }

    /**
     * Retrieve equipment information.
     *
     * @param $image
     *
     * @return array
     */
    protected function info($image)
    {
        $equipment = HtmlDomParser::str_get_html($image->getAttribute('title'));

        list($quality, $slot) = explode(' ', trim($equipment->find('b', 0)->text()), 2);

        return [
            'attributes' => [
                'server' => $this->server,
                'equipment_id' => intval(str_after(trim($equipment->find('bdo', 0)->text()), '#')),
            ],
            'values' => [
                'quality' => $quality,
                'slot' => $slot,
            ],
            'parameters' => $this->parameters($equipment->find('p')),
        ];
    }

    /**
     * Retrieve equipment parameters.
     *
     * @param $parameters
     *
     * @return array
     */
    protected function parameters($parameters)
    {
        $info = [];

        foreach ($parameters as $parameter) {
            list($type, $value) = explode(' by ', substr($parameter->text(), 2));

            $info[] = Parameter::firstOrCreate([
                'type' => title_case($type),
                'value' => floatval($value),
            ]);
        }

        return array_unique($info);
    }
}
