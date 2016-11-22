<?php namespace App\Esim;

use Illuminate\Database\Eloquent\Model;

class Esim extends Model {

    public function scopeServer($query, $param)
    {
        $query->where('server', '=', $param);
    }

    public function scopeBattleId($query, $param)
    {
        $query->where('battle_id', '=', $param);
    }

    public function scopeRound($query, $param)
    {
        $query->where('round', '=', $param);
    }

    public static function server_name_to_id($server_name = null)
    {
        $server_list = [
            'primera' => 1,
            'secura' => 2,
            'suna' => 3,
            'oriental' => 4
        ];

        if ( ! array_key_exists($server_name, $server_list))
        {
            return null;
        }

        return $server_list[$server_name];
    }

    public static function curl_get($url, $esim = false, $server_name = 'secura')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        if (true === $esim)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path('esim_cookie/cookie_' . $server_name));
            curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path('esim_cookie/cookie_' . $server_name));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public static function esim_login($server_name = 'secura')
    {
        $login_data = [
            'login' => 'Level5',
            'password' => 'esimtwbotlevel5',
            'remember' => 'true',
            'facebookAdId' => '',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'http://' . $server_name . '.e-sim.org/login.html');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path('esim_cookie/cookie_' . $server_name));
        curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path('esim_cookie/cookie_' . $server_name));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($login_data));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_exec($ch);
        curl_close($ch);
    }

}