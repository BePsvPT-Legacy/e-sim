<?php

namespace App\Http\Middleware;

use Agent;
use App;
use Carbon\Carbon;
use Closure;
use Cookie;

class Localization
{
    /**
     * Supported locales.
     *
     * @var array
     */
    protected $locales = ['zh-TW', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lng = config('app.locale');

        if (in_array($request->query('lng'), $this->locales)) {
            $lng = $request->query('lng');
        } elseif ($request->hasCookie('lng')) {
            $lng = $request->cookie('lng');
        } else {
            $lowerLocales = array_map('mb_strtolower', $this->locales);

            foreach (Agent::languages() as $language) {
                $index = array_search($language, $lowerLocales);

                if (false !== $index) {
                    $lng = $this->locales[$index];

                    break;
                }
            }
        }

        App::setLocale($lng);
        Carbon::setLocale(str_replace('-', '_', $lng));

        $response = $next($request);

        $response->cookie( Cookie::forever('lng', $lng));

        return $response;
    }
}
