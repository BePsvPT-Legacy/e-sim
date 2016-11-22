<?php namespace App\Commands;

abstract class Command {

    public function server_name_to_id($server_name = null)
    {
        $server_list = [
            'primera' => 1,
            'secura' => 2,
            'suna' => 3,
            'oriental' => 4
        ];

        if ( ! in_array($server_name, $server_list))
        {
            return null;
        }

        return $server_list[$server_name];
    }

    public function server_id_to_name($server_id = null)
    {
        if ($server_id < 1 || $server_id > 4)
        {
            return null;
        }

        $server_list = ['primera', 'secura', 'suna', 'oriental'];

        return $server_list[($server_id - 1)];
    }

    public function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
