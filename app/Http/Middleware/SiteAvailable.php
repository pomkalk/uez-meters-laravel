<?php

namespace App\Http\Middleware;

use Closure;
use AppConfig;

class SiteAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!AppConfig::get('site.available'))
        {
            abort(503);
        }
        return $next($request);
    }
}
