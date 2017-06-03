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

if (! function_exists('nf')) {
    /**
     * Number format wrapper.
     *
     * @param $number
     * @param int $decimals
     *
     * @return string
     */
    function nf($number, $decimals = 0)
    {
        return number_format($number, $decimals);
    }
}
