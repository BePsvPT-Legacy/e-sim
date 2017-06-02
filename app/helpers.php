<?php

if (! function_exists('fetch_json')) {
    /**
     * Send http request and return json decode content.
     *
     * @param string $url
     *
     * @return array
     */
    function fetch_json($url)
    {
        $response = (new GuzzleHttp\Client)->get($url);

        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }
}
