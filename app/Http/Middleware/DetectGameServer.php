<?php namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DetectGameServer {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $server_list = ['primera', 'secura', 'suna'/*, 'oriental'*/];

        $server = $request->segment(1);

        if ( ! in_array($server, $server_list))
        {
            throw new NotFoundHttpException;
        }
        
        return $next($request);
    }

}
