<?php

namespace App\Http\Middleware;

use Agent;
use App;
use Carbon\Carbon;
use Closure;

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
        $lowerLocales = array_map('mb_strtolower', $this->locales);

        foreach (Agent::languages() as $language) {
            $index = array_search($language, $lowerLocales);

            if (false !== $index) {
                App::setLocale($this->locales[$index]);
                Carbon::setLocale(str_replace('-', '_', $this->locales[$index]));

                break;
            }
        }

        return $next($request);
    }
}
